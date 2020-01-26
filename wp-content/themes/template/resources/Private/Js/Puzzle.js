
class Puzzle {
 constructor() {
     this.moves = 1;
     this.winStreak = 1;
 }



    // var moves = 1, winStreak = 1;// totalGames = 1;
     startListners(){
        $('.box').on('click', this.checkRule);
        $('#shuffle').on('click', function(){
            this.shuffleBoard();
        });
    }

     refreshGrid(target, text){
        let whoteElement = target + " .box-number";
        $(whoteElement).text(text);
    }

     isValidPattern(pattern){
        var inversion = 0;
        for (let i=0; i<9;i++){
            for (let j=i+1;j<9;j++){
                if(pattern[i]!=='-' && pattern[j]!== '-' && parseInt(pattern[i]) > parseInt(pattern[j])){
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


     shuffleBoard(){
        //$('#winStreak').text('Wins: '+winStreak+'/'+totalGames);
        $('#shuffle').css('background', 'white');
        $('#shuffle').css('color', 'black');
        $('#shuffle').html('<b>Reset</b>');
        var newBoardPattern = _.shuffle(['1', '2' , '3', '4', '5', '6', '7', '8', '-']);
        if(this.isValidPattern(newBoardPattern)){
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
            this.startListners();
            this.checkGoal();
            $('#msg').text('');
            $('#movesInc').text('posuny: 0');
            this.moves = 1;
        } else {
            this.shuffleBoard();
        }
    }

     checkGoal() {
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

     checkRule(){
        var sourceId = '#'+this.id;
        var sourceText =  $(sourceId).text();
        var targetText = '-';
        $('.box').each(function(){
            var id = '#'+this.id;
            if ($(id).text() === '-'){
                if (this.ruleEngine(sourceId, id) === true){
                    this.refreshGrid(id, sourceText);
                    $(id).css('background-color', 'steelBlue');
                    this.refreshGrid(sourceId, targetText);
                    $(sourceId).css('background-color', 'lightblue');
                    // updateMessage('Dobrý ťah : '+sourceText, 'darkBlue');
                    if (this.checkGoal()){
                        this.updateMessage('Vyhrali ste!', 'green');
                        $('#winStreak').text('Wins: '+this.winStreak++);
                        $('.box').off('click');
                        $('#shuffle').css('background', 'green');
                        $('#shuffle').css('color', 'white');
                        $('#shuffle').html('<b>Ešte raz!</b>');
                    }
                    $('#movesInc').text('posuny: '+this.moves++);
                } else if (sourceId !== id) {
                    // updateMessage('Zlý ťah!', 'Red');
                }
                return false;
            }
        });
    }

     ruleEngine(sourceId, targetId){
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

     startGame(){
        this.startListners();
        this.shuffleBoard();
        this.checkGoal();
        $('#msg').text('');
        $('#movesInc').text('posuny: 0');
        this.moves = 1;
    }

     updateMessage(msg, color){
        $('#msg').html(msg);
        $('#msg').css('color', color);

        $('#gameRules').css('display', 'none');
    }
}
