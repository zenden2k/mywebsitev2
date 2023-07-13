// TinyMCE config
const editorOptions = {
    type: Object,
    default: () => { return {
        height: 500,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor code',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount codesample'
        ],
        toolbar:
            'undo redo | formatselect | bold italic backcolor | \
            alignleft aligncenter alignright alignjustify | \
            bullist numlist outdent indent image code codesample | removeformat | help',
        relative_urls : false,
        remove_script_host : true,
        images_upload_url: "/api/upload-image",
        codesample_languages: [
            { text: 'HTML/XML', value: 'markup' },
            { text: 'JavaScript', value: 'javascript' },
            { text: 'CSS', value: 'css' },
            { text: 'PHP', value: 'php' },
            { text: 'Ruby', value: 'ruby' },
            { text: 'Python', value: 'python' },
            { text: 'Java', value: 'java' },
            { text: 'C', value: 'c' },
            { text: 'C#', value: 'csharp' },
            { text: 'C++', value: 'cpp' },
            { text: 'ObjectPascal', value: 'objectpascal' },
        ],
        //document_base_url: '/'
    }}
}

export function showToast(success, message){
    const options = {
        class: success? 'bg-success': 'bg-danger',
        title: success? 'Success': "Failure",
        subtitle: '',
        body: success ? message: 'Operation failed',
        autohide: true,
        delay: 3000,
    }
    $(window.document).Toasts('create', options);
}
export default editorOptions
