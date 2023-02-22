@if( isset($allFiles) )
<div style="max-height: 300px; overflow-y: auto;">
	<table class="table table-bordered">
	<tr style="background-color: #ccc">
		<th>File</th>
		<th>Size</th>
		<th>Name</th>
		<th>Uploaded</th>
	</tr>
	@foreach( $allFiles as $fl)
		<tr id="File_{{ $fl->id }}" class="fileTr">
			<td>
			  @if( $fl->extension == 'pdf' )
              <i class="fa fa-file-pdf-o base-red" aria-hidden="true"></i> ({{ $fl->extension }})
              @elseif( $fl->extension == 'doc' || $fl->extension == 'docx' )
              <i class="fa fa-file-word-o base-blue" aria-hidden="true"></i> ({{ $fl->extension }})
              @elseif( $fl->extension == 'xls' || $fl->extension == 'csv' || $fl->extension == 'xlsx' )
              <i class="fa fa-file-excel-o base-green" aria-hidden="true"></i> ({{ $fl->extension }})
              @elseif( $fl->extension == 'ppt' || $fl->extension == 'pptx' )
              <i class="fa fa-file-powerpoint-o base-red" aria-hidden="true"></i> ({{ $fl->extension }})
              @else
              <i class="fa fa-file-text" aria-hidden="true"></i> ({{ $fl->extension }})
              @endif
			</td>
			<td>{{ sizeFilter($fl->size) }}</td>
			<td>{{ $fl->name }}</td>
			<td>
				{{ date('d-m-y', strtotime( $fl->created_at ) ) }}
				<input type="hidden" id="fileName_{{ $fl->id }}" value="{{ $fl->name }}">
				<input type="hidden" id="fileTitle_{{ $fl->id }}" value="{{ $fl->title }}">
				<input type="hidden" id="fileCaption_{{ $fl->id }}" value="{{ $fl->caption }}">
				<input type="hidden" id="fileDesc_{{ $fl->id }}" value="{{ $fl->details }}">
			</td>
		</tr>
	@endforeach
	</table> 
</div>
<div class="row">
	<div class="col-md-12">
		{{ $allFiles->appends(request()->query())->links() }}
	</div>
</div>
<!--{!! $allFiles->render() !!}-->
@endif