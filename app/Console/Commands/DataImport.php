<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Municipality;
use App\Models\MunicipalityWorker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Sunra\PhpSimple\HtmlDomParser;

class DataImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from a url';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "\nImport in progress! - this may take a while.\n";

        $i = 0;
        $pages = ['0', '500', '1000', '1500', '2000', '2500'];
        $url   = 'https://www.e-obce.sk/zoznam_vsetkych_obci.html?strana='; 

        foreach ($pages as $page) {
            $i += $this->parseDataFromUrl($url . $page);
        }

        echo "\nImport is completed!\n";
        echo $i . " municipalities imported.\n";
    }

    /**
     * @param $url
     * @return int
     */
    private function parseDataFromUrl($url)
    {
        $dom      = HtmlDomParser::file_get_html( $url );
        $elements = $dom->find('a');
        $i = 0;

        foreach ($elements as $element) {
            if (strpos($element, '/obec/') !== false) {
                $url_id = str_replace('https://www.e-obce.sk/obec/', '',$element->href);
                $url_id = substr($url_id, 0, strpos($url_id, '/'));
                $municipality = Municipality::where('url_id', $url_id)->first();
                if (is_null($municipality)) {
                    $i++;
                    $name = $element->text();
                    $data = $this->getMunicipalityData($element->href);

                    $municipality_id = $this->importMunicipalityData($url_id, $name, $data);
                    $i++;
                    if (!is_null($municipality_id) && isset($data['coat_of_arms'])) {
                        $this->saveCoatOfArms($data['coat_of_arms'], $municipality_id);
                    }
                }
            }
        }

        return $i;
    }

    /**
     * @param $url
     * @return array
     */
    private function getMunicipalityData($url)
    {
        $dom   = HtmlDomParser::file_get_html( $url );
        $elements = $dom->find('table[cellspacing=3] td');
        $data  = [];
        $items = [
            'Starosta:'  => 'mayor',
            'Primátor:'  => 'mayor',
            'Prednosta:' => 'superintendent',
            'Fax:'       => 'fax',
            '.gif'       => 'coat_of_arms'
        ];

        $phone = null;
        foreach ($elements as $element) {
            if (!isset($data['web']) && strpos($element->text(), 'Web:') !== false) {
                $data['web']  = trim($element->next_sibling()->plaintext);
                $str          = trim($element->prev_sibling()->plaintext);
                $data['zip']  = substr($str, 0, 6);
                $data['city_name'] = substr($str, 6);
                continue;
            }
            if (!isset($data['email']) && strpos($element->text(), 'Email:') !== false) {
                $data['email']  = trim($element->next_sibling()->plaintext);
                $data['street'] = trim($element->prev_sibling()->plaintext);
                continue;
            }
            if (is_null($phone) && strpos($element->text(), 'Mobil:') !== false) {
                $phone = trim($element->next_sibling()->plaintext);
                continue;
            }
            if (!isset($data['phone']) && strpos($element->text(), 'Tel:') !== false) {
                $data['phone'] = trim($element->next_sibling()->plaintext);
                continue;
            }

            foreach ($items as $tag => $item) {
                if (!isset($data[$item]) && strpos($element->text(), $tag) !== false) {
                    $data[$item] = trim($element->next_sibling()->plaintext);
                    unset($items[$tag]);
                    break;
                }
            }
        }
        if (!isset($data['zip']) || !isset($data['street']) || !isset($data['city_name'])) {
            $data = $this->getAddress($data, $dom);
        }

        if (!is_null($phone)) {
            $data['phone'] = $phone;
        }

        $data['position']     = isset($items['Starosta:']) ? 'Primátor' : 'Starosta';
        $data['coat_of_arms'] = $this->getUrlOfCoatOfArms($dom);

        return $data;
    }

    /**
     * If Address is not set try other way.
     * @param $data
     * @param $dom
     * @return mixed
     */
    private function getAddress($data, $dom)
    {
        $divs = $dom->find('div[class=adbox]');

        foreach ($divs as $div) {
            if (!isset($data['street'])) {
                $data['street'] = trim($div->next_sibling()
                    ->last_child()
                    ->prev_sibling()
                    ->first_child()
                    ->plaintext
                );
            }
            $str = trim($div->next_sibling()
                ->last_child()
                ->first_child()
                ->plaintext
            );
            if (!isset($data['zip'])) {
                $data['zip'] = substr($str, 0, 6);
            }
            if (!isset($data['city_name'])) {
                $data['city_name'] = substr($str, 6);
            }

            break;
        }

        return $data;
    }

    /**
     * @param $dom
     * @return |null
     */
    private function getUrlOfCoatOfArms($dom)
    {
        $elements = $dom->find('img');
        foreach ($elements as $element) {
            if (!isset($data['img']) && strpos($element->src, '/erb/') !== false) {

                return $element->src;
            }
        }

        return null;
    }

    /**
     * @param $url_id
     * @param $name
     * @param $data
     * @return int|null
     */
    private function importMunicipalityData($url_id, $name, $data)
    {
        $address_id = $this->getAddressId($data);

        if (!is_null($address_id)) {
            $municipality_id = $this->saveMunicipality($url_id, $name, $data, $address_id);

            try {
                if (!is_null($municipality_id) && isset($data['mayor'])) {
                    $municipality_worker =  new MunicipalityWorker();
                    $municipality_worker->name     = isset($data['mayor'])    ? $data['mayor']    : null;
                    $municipality_worker->position = isset($data['position']) ? $data['position'] : null;
                    $municipality_worker->municipality_id = $municipality_id;
                    $municipality_worker->save();

                    if (isset($data['superintendent'])) {
                        $municipality_worker =  new MunicipalityWorker();
                        $municipality_worker->name     = isset($data['superintendent']) ? $data['superintendent'] : null;
                        $municipality_worker->position = 'Prednosta';
                        $municipality_worker->municipality_id = $municipality_id;
                        $municipality_worker->save();
                    }
                }
            } catch (\Exception $e) {
                echo "Error - Municipality worker insert failed!.\n";

                return null;
            }
        }

        return isset($municipality_id) ? $municipality_id : null;
    }

    /**
     * @param $data
     * @return int|mixed|null
     */
    private function getAddressId($data)
    {
        $city_name = isset($data['city_name']) ? $data['city_name']  : null;
        $zip    = isset($data['zip'])    ? $data['zip']     : null;
        $street = isset($data['street']) ? $data['street']  : null;

        $address = Address::where('city_name', $city_name)
            ->where('zip'   , $zip)
            ->where('street', $street)
            ->first();

        if (is_null($address)) {
            try {
                $address = new Address();
                $address->city_name = isset($data['city_name'])? $data['city_name']: null;
                $address->zip       = isset($data['zip'])? $data['zip']  : null;
                $address->street    = isset($data['street'])? $data['street']  : null;
                $address->save();
            } catch (\Exception $e) {
                echo "Error - Address insert failed!.\n";

                return null;
            }
        }

        return isset($address->id) ? $address->id : null;
    }

    /**
     * @param $url_id
     * @param $name
     * @param $data
     * @param $address_id
     * @return int|null
     */
    public function saveMunicipality($url_id, $name, $data, $address_id)
    {
        try {
            $municipality = new Municipality();
            $municipality->name   = $name;
            $municipality->url_id = $url_id;
            $municipality->email  = isset($data['email']) ? $data['email'] : null;
            $municipality->fax    = isset($data['fax'])   ? $data['fax']   : null;
            $municipality->web    = isset($data['web'])   ? $data['web']   : null;
            $municipality->phone  = isset($data['phone']) ? $data['phone'] : null;
            $municipality->address_id = $address_id;
            $municipality->save();
        } catch (\Exception $e) {
            echo "Error - Municipality insert failed!.\n";
            return null;
        }

        return isset($municipality->id) ? $municipality->id : null;
    }

    /**
     * @param $url
     * @param $municipality_id
     * @return string
     */
    private function saveCoatOfArms($url, $municipality_id)
    {
        try {
            $contents = file_get_contents($url);

            Storage::disk('public')->put('images/'. $municipality_id .'.gif', $contents);
        } catch (\Exception $e) {
            echo "\nError - Image saving failed!.\n";

            return $e->getMessage();
        }
    }
}
