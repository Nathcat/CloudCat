function cloud_get_file_tree(on_success, on_error) {
    fetch("/get-file-tree.php", {
        method: "GET",
        credentials: "include"
    }).then((r) => r.json()).then((r) => {
        if (r.status == "success") on_success(r.tree);
        else on_error(r.message);
    });
}

function cloud_get_dir_contents(dir, callback) {
    let path = dir.split("/");

    cloud_get_file_tree((tree) => {
        let currentDir = tree;

        for (let i = 1; i < path.length; i++) {
            if (path[i] === "") break;
            if (currentDir[path[i]] !== undefined) currentDir = currentDir[path[i]];
            else { alert("Specified path cannot be found!"); return; }
        }

        callback(currentDir["."]);
    }, (m) => alert(m));
}