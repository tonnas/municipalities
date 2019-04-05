<div>
    <div class="container" style="min-width: 315px">
        <div class="row up_row">
            <div class="col-lg-6">
                <a href="{{ route('index')  }}">
                    <img src="{{ route('index')}}/logo.png" class="img_logo">
                </a>
                <div style="margin-bottom: 10px">
                    <a href="#">O nás</a>
                    <a href="#" style="margin-left: 8%;">Zoznam miest</a>
                    <a href="#" style="margin-left: 8%;">Inšpekcia</a>
                    <a href="#" style="margin-left: 8%;">Kontakt</a>
                </div>
            </div>
            <div class="col-lg-6" align="right" style="display: inline-block;">
                <form class="input_search">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <select class="btn btn-mini mr-1" style="opacity: 0.6">
                                <option value="en">EN</option>
                                <option value="sk">SK</option>
                                <option value="cz">CZ</option>
                            </select>
                        </div>
                        <input type="text" class="form-control input_form">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                        <div class="input-group-btn">
                            <button class="btn btn-success" type="submit" style="margin-left: 2px;border-radius: 3px !important;">Prihlásiť</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .img_logo {
        width: 160px;
        margin-bottom: 20px;
    }
    .up_row {
        min-height: 70px;
        font-size: smaller;
    }
    .input_search {
        min-width: 270px;
        width: 60%;
    }
    .input_form {
        border-radius: 3px !important;
    }
</style>