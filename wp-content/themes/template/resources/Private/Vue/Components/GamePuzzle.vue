<template>
    <div class="container mt-4">
        <div class="game-tic-tac-toe-info text-center p-2 mb-4" style="background-color: #eee;">
            <h3>Pravidlá</h3>
            <p>Usporiadať čísla podľa správneho poradia v čo najkratšom čase.<br>
            Tabuľka pod číslami zobrazuje ich aktuálne správne poradie.</p>
        </div>
        <div id='playBoard'>
            <div class="playBoard-content">
                <div id ='grid' class="mb-4">
                    <div class = 'box' id='s1'>
                        <div class="box-number">1</div>
                    </div>
                    <div class = 'box' id='s2'>
                        <div class="box-number">8</div>
                    </div>
                    <div class = 'box' id='s3'>
                        <div class="box-number">2</div>
                    </div>
                    <div class = 'box' id='s4'>
                        <div class="box-number">-</div>
                    </div>
                    <div class = 'box' id='s5'>
                        <div class="box-number">4</div>
                    </div>
                    <div class = 'box' id='s6'>
                        <div class="box-number">3</div>
                    </div>
                    <div class = 'box' id='s7'>
                        <div class="box-number">7</div>
                    </div>
                    <div class = 'box' id='s8'>
                        <div class="box-number">6</div>
                    </div>
                    <div class = 'box'id='s9'>
                        <div class="box-number">5</div>
                    </div>
                </div>
                <div id='controlPanel'>
                    <div class="row">
                        <div class="col-4 pr-0 pr-md-2-half">
                            <div id='movesInc'>posuny: 0</div>
                            <div id='msg' style="display: none"></div>
                        </div>
                        <div class="col pl-0 pl-sm-1">
                            <div class="row">
                                <div class="col pr-0 pr-sm-4">
                                    <table id='solution'>
                                        <tr><th id='ss1'>1</th><th id='ss2'>2</th><th id='ss3'>3</th></tr>
                                        <tr><th id='ss4'>4</th><th id='ss5'>5</th><th id='ss6'>6</th></tr>
                                        <tr><th id='ss7'>7</th><th id='ss8'>8</th><th id='ss9'>-</th></tr>
                                    </table>
                                </div>
                                <div class="col d-flex flex-column justify-content-between pl-sm-0 pl-1">
                                    <button id='shuffle'><b>Zamiešaj</b></button>
                                    <div id='winStreak'>Výhry: 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import {_} from 'vue-underscore';
    export default {
        data() {
            return {
                pageLoad: false
            }
        },
        mounted() {
            var moves = 1, winStreak = 1;// totalGames = 1;
            function startListners(){
                $('.box').on('click', checkRule);
                $('#shuffle').on('click', function(){
                    shuffleBoard();
                });
            }

            function refreshGrid(target, text){
                let whoteElement = target + " .box-number";
                $(whoteElement).text(text);
            }

            function isValidPattern(pattern){
                var inversion = 0;
                for (let i=0; i<9;i++){
                    for (let j=i+1;j<9;j++){
                        if(pattern[i]!='-' && pattern[j]!= '-' && parseInt(pattern[i]) > parseInt(pattern[j])){
                            inversion++;
                        }
                    }
                }
                if(inversion%2 === 0){
                    return true;
                } else {
                    return false;
                }
            }


            function shuffleBoard(){
                //$('#winStreak').text('Wins: '+winStreak+'/'+totalGames);
                $('#shuffle').css('background', 'white');
                $('#shuffle').css('color', 'black');
                $('#shuffle').html('<b>Reset</b>');
                var newBoardPattern = _.shuffle(['1', '2' , '3', '4', '5', '6', '7', '8', '-']);
                if(isValidPattern(newBoardPattern)){
                    //totalGames++;
                    $('.box').each(function(){
                        var id = '#'+this.id;
                        if ($(id).text() === '-'){
                            $(id).css('background-color', 'steelBlue');
                        }
                    });
                    $('#s1 .box-number').text(newBoardPattern[0]);
                    $('#s2 .box-number').text(newBoardPattern[1]);
                    $('#s3 .box-number').text(newBoardPattern[2]);
                    $('#s4 .box-number').text(newBoardPattern[3]);
                    $('#s5 .box-number').text(newBoardPattern[4]);
                    $('#s6 .box-number').text(newBoardPattern[5]);
                    $('#s7 .box-number').text(newBoardPattern[6]);
                    $('#s8 .box-number').text(newBoardPattern[7]);
                    $('#s9 .box-number').text(newBoardPattern[8]);
                    $('.box').each(function(){
                        var id = '#'+this.id;
                        if ($(id).text() === '-'){
                            $(id).css('background-color', 'lightblue');
                        }
                    });
                    startListners();
                    checkGoal();
                    $('#msg').text('');
                    $('#movesInc').text('posuny: 0');
                    moves = 1;
                } else {
                    shuffleBoard();
                }
            }

            function checkGoal() {
                var count = 0, loop = 1;
                $('.box').each(function(){
                    var id = '#'+this.id, sid='#s'+this.id;
                    if (parseInt($(id).text()) === loop++){
                        $(id).css('color', '#7FFE01');
                        $(sid).css('background', 'lightgreen');
                        count++;
                    } else {
                        $(id).css('color', 'white');
                        $(sid).css('background', 'white');
                    }
                });
                if (count === 8){
                    return true;
                } else {
                    return false;
                }
            }

            function checkRule(){
                var sourceId = '#'+this.id;
                var sourceText =  $(sourceId).text();
                var targetText = '-';
                $('.box').each(function(){
                    var id = '#'+this.id;
                    if ($(id).text() === '-'){
                        if (ruleEngine(sourceId, id)){
                            refreshGrid(id, sourceText);
                            $(id).css('background-color', 'steelBlue');
                            refreshGrid(sourceId, targetText);
                            $(sourceId).css('background-color', 'lightblue');
                            updateMessage('Dobrý ťah : '+sourceText, 'darkBlue');
                            if (checkGoal()){
                                updateMessage('Vyhrali ste!', 'green');
                                $('#winStreak').text('Wins: '+winStreak++);
                                $('.box').off('click');
                                $('#shuffle').css('background', 'green');
                                $('#shuffle').css('color', 'white');
                                $('#shuffle').html('<b>Ešte raz!</b>');
                            }
                            $('#movesInc').text('posuny: '+moves++);
                        } else if (sourceId != id) {
                            updateMessage('Zlý ťah!', 'Red');
                        }
                        return false;
                    }
                });
            }

            function ruleEngine(sourceId, targetId){
                switch(targetId){
                    case '#s9':
                        if (sourceId === '#s8' || sourceId === '#s6'){
                            return true;
                        } else {
                            return false;
                        }
                    case '#s8':
                        if (sourceId === '#s7' || sourceId === '#s9' || sourceId === '#s5'){
                            return true;
                        } else {
                            return false;
                        }
                    case '#s7':
                        if (sourceId === '#s8' || sourceId === '#s4'){
                            return true;
                        } else {
                            return false;
                        }
                    case '#s6':
                        if (sourceId === '#s3' || sourceId === '#s5' || sourceId === '#s9'){
                            return true;
                        } else {
                            return false;
                        }
                    case '#s5':
                        if (sourceId === '#s2' || sourceId === '#s4' || sourceId === '#s8' || sourceId === '#s6'){
                            return true;
                        } else {
                            return false;
                        }
                    case '#s4':
                        if (sourceId === '#s1' || sourceId === '#s7' || sourceId === '#s5'){
                            return true;
                        } else {
                            return false;
                        }
                    case '#s3':
                        if (sourceId === '#s2' || sourceId === '#s6'){
                            return true;
                        } else {
                            return false;
                        }
                    case '#s2':
                        if (sourceId === '#s1' || sourceId === '#s3' || sourceId === '#s5'){
                            return true;
                        } else {
                            return false;
                        }
                    case '#s1':
                        if (sourceId === '#s2' || sourceId === '#s4'){
                            return true;
                        } else {
                            return false;
                        }
                }
            }

            function startGame(){
                startListners();
                shuffleBoard();
                checkGoal();
                $('#msg').text('');
                $('#movesInc').text('posuny: 0');
                moves = 1;
            }

            function updateMessage(msg, color){
                $('#msg').html(msg);
                $('#msg').css('color', color);
            }

            startGame();
        },
    }

</script>
