@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-end mb-2">
	<a href="{{route('posts.create')}}" class="btn btn-success">
		Add Post
	</a>
</div>


<div class="card card-default">
	<div class="card-header">
		Posts
	</div>
	<div class="card-body">
		@if($posts->count() > 0)
		<table class="table">
			<thead>
				<th>Image</th>
				<th>Title</th>
				<th></th>
				<th></th> 
			</thead>
			<tbody>
				@foreach($posts as $post)
					<tr>
						<td>
							{{--using asset method gives full path--}}
							<img class="post-img" src="{{asset('/storage')}}/{{$post->image}}" alt="">
						</td>
						<td>
							{{$post->title}}
						</td>
						<td>
							@unless($post->trashed())
							<a href="{{route('posts.edit', $post->id)}}" class="btn btn-info btn-sm">Edit</a>
							@endunless
						</td>
						<td>
							<form action="{{route('posts.destroy', $post->id)}}" method="post">
								@csrf
								@method('DELETE')
								{{--just trash post and not delete permanently from database--}}
							<button type="submit" class="btn btn-danger btn-sm">{{$post->trashed() ? 'Delete' : 'Trash'}}</button>
							</form>
						</td> 
					</tr>
				@endforeach
			</tbody>
		</table> 
		@else
			<h3 class="text-center">No Posts to show</h3>
		@endif
		
		<form action="" method="post" id="deleteCategoryForm">
			@csrf
			@method('DELETE')
			<!-- Modal -->
			<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="deleteModalLabel">Delete Category</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			      	<p class="text-center text-bold">
			      		Are you sure you want to delete this category?
			      	</p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Go back!</button>
			        <button type="submit" class="btn btn-danger">Yes, Delete!</button>
			      </div>
			    </div>
			  </div>
			</div>
		</form>
		

	</div>
</div>

@endsection

@section('scripts')
	<script>
		function handleDelete(id){
			var form = document.getElementById('deleteCategoryForm')
			form.action = '/categories/' + id
			$('#deleteModal').modal('show');
		}
	</script>
@endsection('scripts')