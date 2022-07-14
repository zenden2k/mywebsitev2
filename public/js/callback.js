function parseParams(str) {
    var pieces = str.split("&"), data = {}, i, parts;
    // process each query pair
    for (i = 0; i < pieces.length; i++) {
        parts = pieces[i].split("=");
        if (parts.length < 2) {
            parts.push("");
        }
        data[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1]);
    }
    return data;
}


$(function() {
    var clipboard = new ClipboardJS(".copy_btn");

    clipboard.on("success", function (e) {
        $("#copy_to_clipboard_btn").text("Copied!");
    });

    clipboard.on("error", function (e) {
        $("#copy_to_clipboard_btn").text("Copy failed :(");
    });

    if (codeFromHash) {
        var obj = parseParams(window.location.hash.substr(1));
        obj["timestamp"] = Date.now()/1000|0; // get UNIX time in seconds

        $('#code_edit').val(JSON.stringify(obj));
    }
});