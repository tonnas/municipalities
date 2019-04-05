<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MunicipalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index', [

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $municipality = DB::table('municipality AS m')
            ->join('municipality_worker AS mw', 'mw.municipality_id', '=' , 'm.id')
            ->join('address AS a', 'a.id', '=' , 'm.address_id')
            ->select(
                'm.*', 'mw.name AS mayor', 'a.latitude', 'a.longitude', 'mw.position', 'a.street',
                'a.city_name', 'a.zip', 'a.id AS address_id'
            )
            ->where('m.url_id', $id)
            ->whereIn('mw.position', ['Starosta','Primátor'])
            ->first();

        if ($municipality) {
            if (is_null($municipality->longitude)) {
                $lon_and_lat = $this->getLonAndLat($municipality->street, $municipality->city_name);

                if(isset($lon_and_lat->lat)) {
                    $address = Address::find($municipality->address_id);

                    $address->latitude  = $lon_and_lat->lat;
                    $address->longitude = $lon_and_lat->lon;
                    $address->update();

                    $municipality->latitude  = $lon_and_lat->lat;
                    $municipality->longitude = $lon_and_lat->lon;
                }
            }

            $emails = explode(",", $municipality->email);

            return view('show', [
                'municipality' => $municipality,
                'emails'       => $emails
            ]);
        }

        throw new NotFoundHttpException();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request)
    {
        $output = "";

        if($request->ajax()) {
            $municipalities = DB::table('municipality AS m')
                ->join('municipality_worker AS mw', 'mw.municipality_id', '=' , 'm.id')
                ->select('m.id', 'm.url_id', 'm.name AS name', 'mw.name AS mayor', 'mw.position')
                ->whereIn('mw.position', ['Starosta','Primátor'])
                ->where('m.name','like', '%'. $request->search . '%')
                ->orWhere('mw.name','like', '%'. $request->search . '%')
                ->limit(5)
                ->get();

            if (isset($municipalities) && !empty($municipalities)) {
                foreach ($municipalities as $key => $municipality) {
                    $output .=
                    '<a href="'.route('index') . '/' .$municipality->url_id .'" class="municipality_suggestion">' .
                        '<div class="municipality_coan_of_arms">' .
                            '<img src="'. route('index'). '/storage/images/' .$municipality->id.'.gif" class="municipality_image">' .
                        '</div>' .
                        '<div class="municipality_info">' .
                           ' <p class="municipality_name">'.$municipality->name.'</p>' .
                            '<p class="municipality_mayor">'.$municipality->position.': '.$municipality->mayor.'</p>' .
                        '</div>' .
                    '</a>';
                }
            }
        }

        return Response($output);
    }

    /**
     * @param $street
     * @param $city_name
     * @return \stdClass
     */
    private function getLonAndLat($street, $city_name)
    {
        $str = $street . ' ' . $city_name;

        if (strpos($street, substr($city_name, 1)) !== false) {
            $str = $street;
        }

        $data = $this->getGeocode($str);
        $ret  = new \stdClass();

        if (isset($data[0]->lat)) {
            $ret->lat = $data[0]->lat;
            $ret->lon = $data[0]->lon;
        }

        return $ret;
    }

    /**
     * @param $str_address
     * @return mixed
     */
    private function getGeocode($str_address)
    {
        $url_first_part = "https://locationiq.com/v1/search_sandbox.php?format=json&q=";
        $url_last_part  = "&accept-language=en";
        $str_address    = urlencode($str_address);

        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url_first_part . $str_address . $url_last_part);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($curlSession));
    }
}
