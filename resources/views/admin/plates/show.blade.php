@extends('layouts.app')

@section('content')
<main class="container">
    {{-- <a href="{{ route('admin.restaurants.show', $plates->$restaurants)}}">Return at restaurant</a> --}}
    <h1>{{ $plates->name}}</h1>

    <a class="btn btn-warning my-3" href="{{ route('admin.plates.edit', $plates->id) }}">EDIT PLATE</a>

    <form class="delete-post-form" action="{{route('admin.plates.destroy', $plates->id)}}" method="POST">
        @csrf    
        @method('DELETE')
        <input class="btn btn-danger" type="submit" value="DELETE">
    </form>

     <div class="row mt-5">
            <div class="col-md-6">
                @if ($plates->image > '')
                <img class="img-fluid rounded" src="{{$plates->image}}" alt="{{$plates->name}}">
                
                @elseif ($plates->image == '')
                <img class="img-fluid rounded" src="https://redi.it/wp-content/uploads/2015/08/not-available.png" alt="{{$plates->name}}">
                
                @endif
            </div>
            <div class="col-md-6">
                {{ $plates->description }}
            </div>    
      </div>

      <ul class="list-group mt-5">
          <li class="list-group-item">
                <strong>Price: </strong> €{{$plates->price}}
          
          <br>
                <strong>Avaiable: </strong> @if ($plates->visibility === 0)
                    No

                    @elseif ($plates->visibility === 1)
                    Yes
                        
                    @endif 
          </li>
      </ul>
                    
</main>
@endsection