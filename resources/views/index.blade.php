@extends('layout')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/index.css') }}">
@stop

@section('content')
    <div class="container">
        <div class="row" >
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="row search_input">
            <br />
            <div class="col-lg-2"></div>
            <div class="col-lg-8 input_content">
                <h1>Vyhľadať v databáze obcí</h1>
                <input type="text" class="form-control" id="search" name="search" placeholder="Zadajte názov">
                <div class="hero_autocomplete" id="hero-search-autocomplete">
                    <div class="auto_return" id="append_here">
                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(document).on('keyup', '#search', function(e){
                if (e.key === "Escape") {
                    $('#append_here').empty();
                } else {
                    var value = $(this).val();
                    if (value.length > 2) {
                        $.ajax({
                            url    :"{{ route('search') }}",
                            method :'GET',
                            data   : {
                                search:value
                            },
                            success:function(data) {
                                $('#append_here').html(data);
                            }
                        })
                    } else {
                        $('#append_here').empty();
                    }
                }
            });
            $(window).click(function() {
                $('#append_here').empty();
            });

            $('.input_content').click(function(event){
                event.stopPropagation();
            });

        });
    </script>
@stop