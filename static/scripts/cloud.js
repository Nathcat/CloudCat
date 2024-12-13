const CLOUD_URL = "https://cloud.nathcat.net";

function cloud_get_file_tree(on_success, on_error) {
    fetch(CLOUD_URL + "/get-file-tree.php", {
        method: "GET",
        credentials: "include"
    }).then((r) => r.json()).then((r) => {
        if (r.status == "success") on_success(r.tree);
        else on_error(r.message);
    });
}

function cloud_get_dir_contents(dir, callback, not_found_callback) {
    let path = dir.split("/");

    cloud_get_file_tree((tree) => {
        let currentDir = tree;

        for (let i = 1; i < path.length; i++) {
            if (path[i] === "") break;
            if (currentDir[path[i]] !== undefined) currentDir = currentDir[path[i]];
            else { not_found_callback(); return; }
        }

        callback(currentDir);
    }, (m) => alert(m));
}

function cloud_upload_file(file, path) {
    let fd = new FormData();
    fd.append("file", file);
    fd.append("displayPath", path);

    fetch("https://cdn.nathcat.net/cloud/upload.php", {
        method: "POST",
        credentials: "include",
        body: fd
    }).then((r) => r.json()).then((r) => {
        if (r.status === "fail") alert (r.message);
        else location.reload();
    });
}

function cloud_delete_file(file, name) {
    if (!confirm("Are you sure you want to delete \"" + name + "\"?")) return;

    let fd = new FormData();
    fd.append("filePath", file);

    fetch("https://cdn.nathcat.net/cloud/delete.php", {
        method: "POST",
        credentials: "include",
        body: fd
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") location.reload();
        else alert(r.message);
    });
}