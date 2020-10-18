@extends('layouts.app')

@section('content')
<!-- Page Content -->
<div class="container">

    <a href="/home">Back to my list</a>
    <!-- Portfolio Item Heading -->
    <h1 class="my-4">{{ $bookInfo['volumeInfo']['title'] ?? 'Title unknown' }},
      <small>{{ $bookInfo['volumeInfo']['publishedDate'] ?? 'Publish date unknown'  }}</small>
    </h1>
  
    <!-- Portfolio Item Row -->
    <div class="row">
  
      <div class="col-md-8 ">
        <img class="mx-auto d-block" src="{{ $bookInfo['volumeInfo']['imageLinks']['thumbnail'] ?? 'http://placehold.it/750x500'  }}" alt="">
      </div>
  
      <div class="col-md-4">
        <h3 class="my-3">Google store</h3>
        <a href="{{ $bookInfo['volumeInfo']['infoLink'] ?? 'Link missing' }}" target="_blank">Link</a>
        <p>{{ $bookInfo['volumeInfo']['pageCount'] ?? 'Unknownw bumber of' }} pages.<p>
        
        @isset($bookInfo['volumeInfo']['dimensions'])
        <h3 class="my-3">Book Dimensions</h3>
        <ul>
          <li>height {{ $bookInfo['volumeInfo']['dimensions']['height'] ?? 'unknown' }}</li>
          <li>width {{ $bookInfo['volumeInfo']['dimensions']['width'] ?? 'unknown' }}</li>
          <li>thickness {{ $bookInfo['volumeInfo']['dimensions']['thickness'] ?? 'unknown' }}</li>
        </ul>
        @endisset

      </div>
  
    </div>
    <!-- /.row -->

  
  </div>
  <!-- /.container -->
  
@endsection
