<?php include 'head.php'; ?>
<body>

<a href="index.php" class="home"><i class="fa fa-home"></i></a>


<div class="container game-container">

    <p class="category text-center mg-top-25"></p>

    <button class="btn btn-primary main-game"></button>
    <div class="phonetic">
        <h4 class="game-title">Phonetic</h4>
        <div class="row">
            <div class="col-6">
                <button class="btn btn-default secondary-game"></button>
            </div>
            <div class="col-6">
                <button class="btn btn-default secondary-game"></button>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <button class="btn btn-default secondary-game"></button>
            </div>
            <div class="col-6">
                <button class="btn btn-default secondary-game"></button>
            </div>
        </div>
    </div>
    <div class="translation">
        <h4 class="game-title">Translations</h4>
        <div class="row">
            <div class="col-6">
                <button class="btn btn-default secondary-game"></button>
            </div>
            <div class="col-6">
                <button class="btn btn-default secondary-game"></button>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <button class="btn btn-default secondary-game"></button>
            </div>
            <div class="col-6">
                <button class="btn btn-default secondary-game"></button>
            </div>
        </div>
    </div>
</div>

<div class="done">
    <h3 class="text-center">Congratulations !</h3>
    <p class="text-center">You've finished your 10 words.</p>

    <button class="btn btn-large game-try center-block">Try again</button>
</div>

<div class="game-footer">
    <button class="disabled validate-game btn btn-large btn-success" disabled="disabled">Valider</button>
</div>

<div class="-alert success">You got the right answer !</div>
<div class="-alert error">You got an error, try again !</div>

<script type="text/javascript" src="public/main.js"></script>
<script type="text/javascript" src="public/play.js"></script>
</body>
</html>