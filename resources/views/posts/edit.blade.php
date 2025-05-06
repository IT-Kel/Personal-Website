@extends('layouts.feed_layout')

@section('content')
<div class="container">
    <h2>Edit Post</h2>

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    
        <div class="form-group">
            <label for="content">Post Content:</label>
            <textarea name="content" id="content" class="form-control" rows="4">{{ old('content', $post->content) }}</textarea>
        </div>
    
        <div class="form-group">
            <label for="media">Post Media (Optional):</label>
            <input type="file" name="media" id="media" class="form-control">
        </div>
    
        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
    
</div>
@endsection
