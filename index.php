<!DOCTYPE html>
<html>

<head>
    <title>CloudCat</title>

    <link rel="stylesheet" href="https://nathcat.net/static/css/new-common.css">
    <link rel="stylesheet" href="/static/styles/browser.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/static/scripts/cloud.js"></script>
</head>

<body>
    <div class="content">
        <?php include("header.php"); ?>
        <script>
            const searchParams = new URLSearchParams(window.location.search);
            let path = "/";
            if (searchParams.has("path")) {
                path = searchParams.get("path");
            }

            if (path.slice(-1) === "/" && path.length !== 1) {
                path = path.substring(0, path.length - 1);
            }

            let parentPath = "";
            if (path !== "/") {
                parentPath = path.split("/");
                parentPath = parentPath.slice(0, parentPath.length - 1);
                parentPath = parentPath.join("/");
            }
        </script>

        <div class="main">
            <div class="row align-center">
                <div class="column align-center justify-center">
                    <input style="width: 75%;" id="file-upload" type="file" />
                    <input style="width: 75%;" id="new-folder-name" type="text" placeholder="New folder name?" />
                </div>
        
                <div class="column align-center justify-center">
                    <button style="width: 75%;" onclick="cloud_upload_file(document.getElementById('file-upload').files[0], path)">Upload file</button>
                    <button style="width: 75%;" onclick="cloud_upload_file(document.getElementById('file-upload').files[0], path + '/' + $('#new-folder-name').val())">Upload file to new folder</button>
                </div>
            </div>

            <div ondrop="fileDropHandler(event);" id="dir-contents" class="column">
                <div class="folder" onclick="location = '/?path=' + parentPath;"><img src="/static/images/iconmonstr-folder-open-thin.svg">
                    <h3><i>Up a level</i></h3>
                </div>
            </div>
        </div>

        <script>
            cloud_get_dir_contents(path, (dir) => {
                let container = document.getElementById("dir-contents");

                let files = dir["."];
                for (const key in dir) {
                    if (key === ".") continue;
                    else {
                        container.innerHTML += "<div onclick=\"location='/?path=" + path + "/" + key + "';\" class='folder'><img src='/static/images/iconmonstr-folder-open-thin.svg'><h3>" + key + "</h3></div>";
                    }
                }

                container.innerHTML += "<div style='margin-left: 25px' class='horizontal-divider'></div>";

                for (const file in files) {
                    container.innerHTML += "<div onclick=\"location='https://cdn.nathcat.net/cloud/" + files[file].filePath + "'\" class='file'><img src='/static/images/iconmonstr-file-thin.svg'><h3>" + files[file].name + "</h3></div>";
                }
            });
        </script>

        <?php include("footer.php"); ?>
    </div>
</body>

</html>