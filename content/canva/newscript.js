/*function kolmunrk(){
    const myCanvas = document.getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas.getContext("2d");
        m.beginPath();
        m.strokeStyle="red";
        m.filStyle="green";
        m.lineWidth=1;
        m.moveTo(145,172);
        m.lineTo(173,65);
        m.lineTo(203,173);
        m.lineTo(145,173);
        m.stroke();
        m.fill();
    }
}

function puhasta(){
    const myCanvas = document.getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas.getContext("2d");
        m.clearRect(0,0,400,600)
    }
}

function nelinurk(){
    const myCanvas = document.getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas.getContext("2d");
        m.fillRect(100,200,50,100); //x, y, laius ,kõrgus
    }
}

function pall(){
    const myCanvas = document.getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas.getContext("2d");
        m.beginPath();
        m.strokeStyle="green";
        m.arc(50, 50, 10, 0, 2*Math.PI, true)
        m.stroke();
        m.fill();
    }
}

function pilt(){
    const myCanvas = document.getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas.getContext("2d");

        const fail=new Image();
        fail.src="backroundbytbetter.png";
        fail.onload = () => {
            m.drawImage(fail, 0,0,400,600);
        }
    }
}
*/

function kolmunrgad(){
    const myCanvas = document. getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas.getContext("2d");

        // 1
        m.beginPath();
        m.strokeStyle="darkgreen";
        m.fillStyle="darkgreen";
        m.lineWidth=2;
        m.moveTo(200,100);
        m.lineTo(120,200);
        m.lineTo(280,200);
        m.lineTo(200,100);
        m.stroke();
        m.fill();

        // 2
        m.beginPath();
        m.strokeStyle="green";
        m.fillStyle="green";
        m.lineWidth=2;
        m.moveTo(200,170);
        m.lineTo(130,270);
        m.lineTo(270,270);
        m.lineTo(200,170);
        m.stroke();
        m.fill();

        // 3
        m.beginPath();
        m.strokeStyle="darkgreen";
        m.fillStyle="darkgreen";
        m. lineWidth=2;
        m.moveTo(200,240);
        m.lineTo(140,340);
        m.lineTo(260,340);
        m.lineTo(200,240);
        m.stroke();
        m.fill();
    }
}

function pallid(){
    const myCanvas = document.getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas. getContext("2d");

        // 1
        m.beginPath();
        m.strokeStyle="red";
        m. fillStyle="red";
        m.lineWidth=2;
        m.arc(200,180,8,0,2*Math.PI,true);
        m.stroke();
        m.fill();

        // 2
        m.beginPath();
        m.strokeStyle="gold";
        m.fillStyle="gold";
        m.lineWidth=2;
        m.arc(160,220,8,0,2*Math.PI,true);
        m.stroke();
        m.fill();

        // 3
        m.beginPath();
        m.strokeStyle="blue";
        m.fillStyle="blue";
        m.lineWidth=2;
        m.arc(240,220,8,0,2*Math.PI,true);
        m.stroke();
        m.fill();

        // 4
        m.beginPath();
        m.strokeStyle="red";
        m.fillStyle="red";
        m.lineWidth=2;
        m.arc(200,250,8,0,2*Math.PI,true);
        m.stroke();
        m.fill();

        // 5
        m.beginPath();
        m.strokeStyle="yellow";
        m.fillStyle="yellow";
        m.lineWidth=2;
        m.arc(150,280,8,0,2*Math.PI,true);
        m.stroke();
        m.fill();

        // 6
        m.beginPath();
        m.strokeStyle="purple";
        m.fillStyle="purple";
        m.lineWidth=2;
        m.arc(250,280,8,0,2*Math.PI,true);
        m.stroke();
        m.fill();

        // 7
        m.beginPath();
        m.strokeStyle="gold";
        m.fillStyle="gold";
        m.lineWidth=2;
        m.arc(200,310,8,0,2*Math.PI,true);
        m.stroke();
        m.fill();

        // 8
        m.beginPath();
        m.strokeStyle="red";
        m.fillStyle="red";
        m.lineWidth=2;
        m.arc(170,310,7,0,2*Math.PI,true);
        m.stroke();
        m.fill();

        // 9
        m.beginPath();
        m.strokeStyle="cyan";
        m.fillStyle="cyan";
        m.lineWidth=2;
        m.arc(230,310,7,0,2*Math.PI,true);
        m.stroke();
        m.fill();
    }
}

function puu(){
    const myCanvas = document.getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas. getContext("2d");
        m.fillStyle="brown";
        m. fillRect(185,340,30,80);
    }
}

function puhasta(){
    const myCanvas = document.getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas.getContext("2d");
        m.clearRect(0,0,400,600);
    }
}

function pilt(){
    const myCanvas = document.getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas.getContext("2d");

        const fail=new Image();
        fail.src="backroundbytbetter.png";
        fail.onload = () => {
            m.drawImage(fail, 0,0,400,600);
        }
    }
}

function taevaline(){
    const myCanvas = document. getElementById("myCanvas");
    if (myCanvas.getContext){
        let m=myCanvas.getContext("2d");
        m.beginPath();
        m.strokeStyle="gold";
        m.fillStyle="gold";
        m.lineWidth=2;
        m.moveTo(200,50);
        m.lineTo(206,70);
        m.lineTo(228,70);
        m.lineTo(212,85);
        m.lineTo(218,105);
        m.lineTo(200,90);
        m.lineTo(182,105);
        m.lineTo(188,85);
        m.lineTo(172,70);
        m.lineTo(194,70);
        m.lineTo(200,50);
        m.stroke();
        m.fill();
    }
}



function joonista(){
    pilt();
    setTimeout(taevaline,1000);
    setTimeout(kolmunrgad,1000);
    setTimeout(puu,1000);
    setTimeout(pallid,1000);
    setTimeout(lumehelvesed,1000);
}

