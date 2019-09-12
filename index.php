<?php include 'head.php'; ?>
<body>

<div class="form-top">
    <div class="container">
        <form class="form-inline" action="play.php">
            <div class="form-group mx-sm-4 mb-3">
                <input type="search" class="form-control" id="search" placeholder="Search a value">
            </div>
            <div class="form-group mx-sm-4 mb-3">
                <select name="category" id="category" class="form-control">
                    <option value="">Filter by category</option>
                </select>
            </div>
            <div class="form-group mx-sm-4 mb-3">
                <button class="btn btn-primary" type="button"  data-toggle="modal" data-target="#modal-login" ><i class="fa fa-sync"></i></button>
                <button type="submit" style="margin-left: 5px;" class="btn btn-success" ><i class="fa fa-play"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="container content">
    <table id="table" class="table table-stripped">
        <thead>
        <tr>
            <th>Chinese</th>
            <th>Phonetic</th>
            <th class="sr-only">Slug</th>
            <th>English</th>
            <th>Category</th>
            <th>Phrases</th>
            <th><i class="fas fa-volume-down"></i></th>
        </tr>
        </thead>
        <tbody id="content">
        </tbody>
    </table>
</div>
<div class="modal" tabindex="-1" id="modal-login" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Login to Duolingo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><small>Your credentials are only used to fetch your own data from duolingo and are not stored in any way.</small></p>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="login">Your duolingo login</label>
                        <input type="text" id="login" placeholder="Duolingo login" name="login" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Your duolingo password</label>
                        <input type="password" id="password" placeholder="Duolingo password" name="password" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="sync">Synchronise</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="public/main.js"></script>
<script type="text/javascript" src="public/index.js"></script>
</body>
<script type="text/template" id="row">
    <?php include 'templates/table-row.html'; ?>
</script>
</html>