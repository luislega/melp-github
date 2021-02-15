<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->

    <style>
        body {
            font-family: 'Nunito';
        }
        button {
            margin: 0 0 10px 10px;
        }
        table#restaurants-table {
            width: calc(100% - 20px);
            margin: 0 10px;
        }
        table.edit-table,
        table#create-table {
            margin-bottom: 20px;
        }
        table.edit-table tr td:first-child,
        table#create-table tr td:first-child {
            text-align: right;
            width: 70px;
            padding-right: 5px;
        }
        td.input-cell {
            width: 400px;
        }
        td.input-cell input {
            width: 100%;
        }
        nav {
            margin-left: 10px;
        }
        .clickable {
            cursor: pointer;
        }
        .hidden {
            display: none;
        }
        .padded-left {
            padding-left: 10px;
        }
        .success {
            background-color: darkseagreen;
        }
    </style>
</head>
<body>

<header>
    <h1 class="padded-left">REST-aurant admin</h1>
    {!! $restaurants->links() !!}
</header>

<div>
    <button class="padded-left clickable" onclick="showForm()">Add restaurant</button>
    <p id="created" class="padded-left success hidden">CREATED!</p>
</div>

<div class="hidden" id="new-restaurant">
    <form id="create-restaurant" onsubmit="createRestaurant">
        <table id="create-table">
            <tbody><tr>
                <td>rating</td>
                <td class="input-cell"><input class="resetable" type="text" name="rating"></td>
            </tr>
            <tr>
                <td>name</td>
                <td class="input-cell"><input class="resetable" type="text" name="name" ></td>
            </tr>
            <tr>
                <td>site</td>
                <td class="input-cell"><input class="resetable" type="text" name="site" ></td>
            </tr>
            <tr>
                <td>email</td>
                <td class="input-cell"><input class="resetable" type="text" name="email" ></td>
            </tr>
            <tr>
                <td>phone</td>
                <td class="input-cell"><input class="resetable" type="text" name="phone" ></td>
            </tr>
            <tr>
                <td>street</td>
                <td class="input-cell"><input class="resetable" type="text" name="street" ></td>
            </tr>
            <tr>
                <td>city</td>
                <td class="input-cell"><input class="resetable" type="text" name="city" ></td>
            </tr>
            <tr>
                <td>state</td>
                <td class="input-cell"><input class="resetable" type="text" name="state" ></td>
            </tr>
            <tr>
                <td>lat</td>
                <td class="input-cell"><input class="resetable" type="text" name="lat" ></td>
            </tr>
            <tr>
                <td>lng</td>
                <td class="input-cell"><input class="resetable" type="text" name="lng" ></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Add">
                </td>
            </tr>
            </tbody></table>
    </form>
</div>

<table id="restaurants-table">
    <tr>
        <th>Name</th>
        <th>Rating</th>
        <th>site</th>
        <th>email</th>
        <th>phone</th>
        <th>street</th>
        <th>city</th>
        <th>state</th>
        <th></th>
        <th></th>
    </tr>
    @foreach($restaurants as $r)
        <tr>
            <td id="name-{{$r['id']}}">{{$r['name']}}</td>
            <td id="rating-{{$r['id']}}">{{$r['rating']+1}}</td>
            <td id="site-{{$r['id']}}"><a href="{{$r['site']}}" target="_blank">{{$r['site']}}</a></td>
            <td id="email-{{$r['id']}}"><a href="mailto:{{$r['email']}}" target="_blank">{{$r['email']}}</a></td>
            <td id="phone-{{$r['id']}}">{{$r['phone']}}</td>
            <td id="street-{{$r['id']}}">{{$r['street']}}</td>
            <td id="city-{{$r['id']}}">{{$r['city']}}</td>
            <td id="state-{{$r['id']}}">{{$r['state']}}</td>
            <td id="map-{{$r['id']}}"><a href="https://google.com/maps/search/?api=1&query={{$r['lat']}},{{$r['lng']}}" target="_blank">Ver en mapa</a></td>
            <td>
                <p>
                <input type="hidden" name="id" value="{{ $r['id'] }}">
                    <span class="glyphicon glyphicon-edit clickable" onclick="toggle('{{$r['id']}}')"></span>
                </p>
            </td>
            <td>
                <p>
                    <form
                        action="/api/restaurants/{!! $r['id'] !!}"
                    >
                        @method('DELETE')
                    <input type="hidden" name="id" value="{{ $r['id'] }}">
                        <span class="delete glyphicon glyphicon-remove clickable" onclick="deleteItem('{{$r['id']}}')"></span>
                    </form>
                </p>
            </td>
        </tr>
        <tr id="id-{{$r['id']}}" class="hidden">
            <td colspan="11">
                <form id="form-{{$r['id']}}">
                    {{--@method('PUT')--}}
                    <table class="edit-table">
                    <input type="hidden" name="id" value="{{ $r['id'] }}">
                    @foreach($r as $k => $v)
                        @if(array_search($k,['id','incrementing','timestamps','exists', 'wasRecentlyCreated'])===false)
                            <tr>
                                <td>{{$k}}</td>
                                <td class="input-cell"><input type="text" name="{{$k}}" value="{!! $k!=='rating'?$r[$k]:$r[$k]+1 !!}"></td>
                            </tr>
                        @endif
                    @endforeach
                        <tr>
                            <td colspan="2">
                                <input type="button" value="Save" onclick="put('{{$r['id']}}')">
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    @endforeach
</table>

<script type="text/javascript">
    var current;
    function toggle(id){
        if(current) document.getElementById('id-'+current).classList.toggle('hidden');
        if(current===id) return;
        document.getElementById('id-'+id).classList.toggle('hidden');
        current = id;
    }

    function put(id){
        const form = document.querySelector("#form-"+id);
        const data = Object.fromEntries(new FormData(form));
        data.rating -= 1;
        fetch(
            '/api/restaurants',
            {
                method: 'PUT',
                body: JSON.stringify(data),
                headers: {
                    'Content-type': 'application/json; charset=UTF-8'
                }
            }
        ).then(function(response){
            if(!response.ok){
                return;
            }
            let idTr = document.querySelector('#id-'+id);
            idTr.classList.add('success');
            setTimeout(function(){
                idTr.classList.add('hidden');
                idTr.classList.remove('success');
            },2000);
            for(var k in data){
                let element;
                if(k=='id'||k=='lat'||k=='lng') continue;
                if(k!='site'&&k!='email'){
                    element = document.querySelector('#'+k+'-'+id);
                }else{
                    element = document.querySelector('#'+k+'-'+id+'>a');
                    element.href = (k=='email'?'mailto:':'')+data[k];
                }
                element.innerHTML = k=='rating'?data[k]+1:data[k];
            }
            let mapElement = document.querySelector('#map-'+id+'>a')
            mapElement.href = 'https://google.com/maps/search/?api=1&query='+data.lat+','+data.lng;
        });
    }

    window.onload = function(){
        var creationForm = document.getElementById('create-restaurant');
        creationForm.onsubmit = function(e){
            const data = Object.fromEntries(new FormData(creationForm));
            e.preventDefault();
            fetch(
                '/api/restaurants',
                {
                    method: "POST",
                    body: JSON.stringify(data),
                    headers: {
                        'Content-type': 'application/json; charset=UTF-8'
                    }
                }
            ).then(response => {
                if(response.ok){
                    document.querySelector('p#created').classList.remove('hidden');
                    document.querySelector('div#new-restaurant').classList.add('hidden');
                    document.querySelectorAll('.resetable').forEach(input=>input.value="");
                }
                setTimeout(function(){
                    document.querySelector('p#created').classList.add('hidden');
                },2000);
            })
        }
    }

    function showForm(){
        document.querySelector('p#created').classList.add('hidden');
        document.getElementById('new-restaurant').classList.toggle('hidden');
    }

    function deleteItem(id){
        fetch(
            '/api/restaurants/'+id,
            {
                method: 'DELETE',
                headers: {
                    'Content-type': 'application/json; charset=UTF-8'
                }
            }
        ).then(function(response){
            if(response.ok){
                location.reload();
            }
        });
    }
</script>

</body>
</html>
