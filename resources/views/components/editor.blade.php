@props(['id', 'customEmailBody'])
@section('styles')
    <style>
        .note-modal-backdrop {
            display: none !important;
        }
    </style>
@endsection

<textarea id="summernote-{{ $id }}" class="summernote-instance w-full" wire:model.live="customEmailBody"></textarea>

<button wire:click="submit()">Submit</button>

<script>
    jQuery(document).ready(function() {

        $('#summernote-{{ $id }}').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen']]
            ],
            fontNames: [
                'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                'Helvetica', 'Impact', 'Lucida Grande', 'Tahoma',
                'Times New Roman', 'Verdana', 'Roboto', 'Open Sans'
            ],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '30', '36', '48'],
            callbacks: {
                onChange: function(contents, $editable) {
                    @this.set('customEmailBody', contents);
                },
                onImageUpload: function(files) {
                    
                }
            }
        });
    });
</script>
