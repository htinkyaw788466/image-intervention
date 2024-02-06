@extends('layouts.master')

@section('title','all galleries')

@section('content')

 @if (Session::has('status'))
     <div class="alert alert-success">
         {{ Session::get('status') }}
     </div>
 @endif

 <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-5">
            <div class="row mt-5">
               @if(count($galleries)>0)
                  @foreach($galleries as $gallery)

                       <div class="col-md-4 mb-4">
                           <div class="card">
                               <div class="card-body p-0">

                                   {{-- <img src="{{asset('upload/'.$gallery->name)}}" alt="" class="img-fluid"> --}}
                                   {{-- <img src="{{ $gallery->image_link }}" alt="" class="img-fluid"> --}}

                                   <img src="{{ asset('storage/galleries/'.$gallery->name) }}" alt="" class="img-fluid">
                               </div>
                               <div class="card">
                                   <a href="{{ asset('storage/galleries/'.$gallery->name) }}" target="_blank"  class="btn btn-info"><i class="far fa-eye"></i></a>
                                   <a href="{{ route('download',$gallery->id) }}" class="btn btn-success"><i class="fas fa-arrow-circle-down"></i></a>
                                <button class="btn btn-danger" type="button" onclick="deleteGallery({{ $gallery->id }})">
                                    <i class="far fa-trash-alt"></i>
                                </button>

                                <form id="delete-form-{{ $gallery->id }}" action="{{ route('gallery.destroy',$gallery->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')

                                </form>  
                               </div>
                           </div>
                       </div>

                       @endforeach

                       @else

                <p>No Image Available</p>
                @endif

            </div>
            {{$galleries->links()}}
        </div>
    </div>
@endsection

<script type="text/javascript">
    function deleteGallery(id){
    	const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
})
swalWithBootstrapButtons.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  type: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, delete it!',
  cancelButtonText: 'No, cancel!',
  reverseButtons: true
}).then((result) => {
  if (result.value) {
     event.preventDefault();
     document.getElementById('delete-form-'+id).submit();
  } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ) {
    swalWithBootstrapButtons.fire(
      'Cancelled',
      'Your imaginary file is safe :)',
      'error'
    )
  }
})
    }
    </script>
