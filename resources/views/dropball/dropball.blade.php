<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Plinko Raffle Sanden</title>
  <!-- Bootstrap CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <script src="assets/js/sweetalert2@11.js"></script>

  <style>
@font-face {
  font-family: 'PixelGameExtrude';
  font-style: normal;
  font-weight: normal;
  src: url('assets/fonts/PixelGameExtrude-BLqwn.woff') format('woff');
}

body {
  font-family: 'PixelGameExtrude', monospace;
  font-size: 1.2rem;
  background: #f8f9fa;
}

    .board-card { max-width: 2120px; margin: 2rem auto; }
    #canvas-wrap { background: #ffffff; border: 2px solid #0d6efd; border-radius: 10px; overflow: hidden; }
    canvas { display:block; }
    .controls .btn { min-width: 120px; }

    #slot-labels {
      height: 40px;
      bottom: 0;
      left: 0;
    }
    #slot-labels > div {
      font-size: 0.95rem;
      color: #0d6efd;
      border-top: 1px solid rgba(13,110,253,0.15);
      padding-top: 6px;
      text-align: center;
      user-select: none;
    }
    .sanden-anniv-img {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;  /* center horizontally */
  align-items: center;      /* center vertically */
  overflow: hidden;         /* prevent overflow */
}

.sanden-anniv-img img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain; /* keep aspect ratio, fit inside container */
  border-radius: 8px;  /* optional: rounded corners */
}

body {
  margin:0;
}

.bg {
  animation:slide 3s ease-in-out infinite alternate;
  background-image: linear-gradient(-60deg, #6c3 50%, #09f 50%);
  bottom:0;
  left:-50%;
  opacity:.5;
  position:fixed;
  right:-50%;
  top:0;
  z-index:-1;
}

.bg2 {
  animation-direction:alternate-reverse;
  animation-duration:4s;
}

.bg3 {
  animation-duration:5s;
}

.content {

  border-radius:.25em;
  /* box-shadow:0 0 .25em rgba(0,0,0,.25); */
  box-sizing:border-box;
  left:50%;
  padding:10vmin;
  position:fixed;
  text-align:center;
  top:50%;
  transform:translate(-50%, -50%);
}

h1 {
  font-family:monospace;
}

@keyframes slide {
  0% {
    transform:translateX(-25%);
  }
  100% {
    transform:translateX(25%);
  }
}
  </style>
</head>
<body>
    <div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>
<div class="content">
 
<!-- <marquee behavior="scroll" direction="left" scrollamount="6" loop="infinite">
   <span style="font-size: 2em; color: red; font-weight: bold; margin-right: 80px;">
      💕 Happy 6th Anniversary  💕

      💕 Sanden Cold Chain Philippines 💕
   </span>
</marquee> -->



  <div class="container" style="width: 1800px;;">
    <div class="card board-card shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <img src="sanden-logo.png" alt="" width="120px;">
          <h1 style="text-align:left;">Ball Drop of Surprises – 6th Year Special</h1>
          <div>
            <span class="badge bg-primary me-2" id="slotCountBadge">6 Slots</span>
            <span class="badge bg-secondary" id="ballCountBadge">Balls: 0</span>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <div class="position-relative mb-3" style="width:790px;height:700px">
              <div id="canvas-wrap" style="width:100%;height:calc(100% - 40px)"></div>
              <div id="slot-labels" class="d-flex position-absolute w-100 text-center"></div>
            </div>
          </div>
    <div class="col-md-4">
    <div class="sanden-anniv-img">
        <img src="assets/img/SCP Anniv 2025 6x5.jpg" alt="Anniversary Poster">
    </div>
    </div>
  <div class="col-md-4">
            <div class="controls d-grid gap-2">
              <button id="dropBtn" class="btn btn-success">Drop 1 Ball (Center)</button>
              <button id="resetBtn" class="btn btn-warning">Shuffle | Reset</button>
            </div>
          </div>
       
        </div>
      </div>
    </div>
  </div>
<audio id="pingSound" src="sounds/spring-vibration-229137-[AudioTrimmer.com].mp3" preload="auto"></audio>
<audio id="winSounds" src="sounds/success_bell-6776.mp3" preload="auto"></audio>


</div>
  <!-- Matter.js -->
  <script src="assets/js/matter.min.js"></script>
  <!-- Bootstrap Bundle JS -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

<script>
const Engine = Matter.Engine,
      Render = Matter.Render,
      World = Matter.World,
      Bodies = Matter.Bodies,
      Composite = Matter.Composite,
      Events = Matter.Events;

const canvasWrap = document.getElementById('canvas-wrap');
const width = canvasWrap.clientWidth;
const height = canvasWrap.clientHeight;

const engine = Engine.create();
engine.gravity.y = 0.32;

const render = Render.create({
  element: canvasWrap,
  engine: engine,
  options: {
    width: width,
    height: height,
    wireframes: false,
    background: '#ffffff'
  }
});

// walls
const thickness = 50;
const walls = [
  Bodies.rectangle(width/2, -thickness/2, width, thickness, { isStatic: true }),
  Bodies.rectangle(width/2, height + thickness/2, width, thickness, { isStatic: true }),
  Bodies.rectangle(-thickness/2, height/2, thickness, height, { isStatic: true }),
  Bodies.rectangle(width + thickness/2, height/2, thickness, height, { isStatic: true })
];
World.add(engine.world, walls);

// pegs
const pegRadius = 11;
const pegRows = [100, 160, 220, 280, 340, 400, 450];
for (let i = 0; i < pegRows.length; i++) {
  const y = pegRows[i];
  const offset = i % 2 === 0 ? 30 : 60;
  for (let x = offset; x <= width - offset; x += 60) {
    const peg = Bodies.circle(x, y, pegRadius, { 
      isStatic: true, 
      label: 'peg', // added label for collision detection
      render: { fillStyle: '#6c757d' } 
    });
    World.add(engine.world, peg);
  }
}

// ---- SLOT SETUP ----
let slotNames = ['SCP Thumbler', '600 Worth of GC', '6 peso coins', 'Journal', 'Sweet Treats', 'Mini Fan'];

function shuffleArray(arr) {
  return arr
    .map(v => ({ v, sort: Math.random() }))
    .sort((a, b) => a.sort - b.sort)
    .map(({ v }) => v);
}
slotNames = shuffleArray(slotNames);

const slotCount = slotNames.length;
document.getElementById('slotCountBadge').textContent = slotCount + ' Slots';

const slotWidth = width / slotCount;
const slotSensors = [];

for (let i = 0; i < slotCount; i++) {
  const left = i * slotWidth;
  const wall = Bodies.rectangle(left, height - 80, 10, 160, { isStatic: true, render: { fillStyle: '#0d6efd' } });
  const wallRight = Bodies.rectangle(left + slotWidth, height - 80, 10, 160, { isStatic: true, render: { fillStyle: '#0d6efd' } });
  World.add(engine.world, [wall, wallRight]);

  const sensor = Bodies.rectangle(left + slotWidth/2, height - 30, slotWidth - 10, 40, {
    isStatic: true,
    isSensor: true,
    label: 'slotSensor' + i,
    render: { visible: false }
  });
  slotSensors.push(sensor);
  World.add(engine.world, sensor);
}

for (let i = 1; i < slotCount; i++) {
  const x = i * slotWidth;
  const divider = Bodies.rectangle(x, height - 30, 6, 120, { isStatic: true, render: { fillStyle: '#0d6efd' } });
  World.add(engine.world, divider);
}

const slotLabelsDiv = document.getElementById('slot-labels');
function resetLabels() {
  slotLabelsDiv.innerHTML = '';
  for (let i = 0; i < slotCount; i++) {
    const label = document.createElement('div');
    label.className = 'flex-fill';
    label.textContent = "🎁";
    //label.textContent = slotNames[i];  // show prize instead of "???"
    slotLabelsDiv.appendChild(label);
  }
}
resetLabels();

const balls = [];
let totalBallsDropped = 0;

const pingSound = document.getElementById('pingSound'); // ping sound

Events.on(engine, 'collisionStart', function(event) {
  const pairs = event.pairs;
  pairs.forEach(p => {
    // Ball hits a peg
    const bodies = [p.bodyA, p.bodyB];
    bodies.forEach(body => {
      if (body.label && body.label.startsWith('ball')) {
        const other = body === p.bodyA ? p.bodyB : p.bodyA;
        if (other.label === 'peg') {
          pingSound.currentTime = 0;
          pingSound.play();
        }
      }
    });

    // Ball hits a slot sensor
// Ball hits a slot sensor
slotSensors.forEach((sensor, idx) => {
  if (p.bodyA === sensor || p.bodyB === sensor) {
    const other = p.bodyA === sensor ? p.bodyB : p.bodyA;
    if (other.label && other.label.startsWith('ball')) {
      if (!other.hasWon) {
        other.hasWon = true;

        // Play win sound
        const winSounds = document.getElementById('winSounds');
        winSounds.currentTime = 0;
        winSounds.play();

        const prize = slotNames[idx];

        // SHOW ALL SLOTS after a win
        for (let i = 0; i < slotCount; i++) {
          slotLabelsDiv.children[i].textContent = slotNames[i];
        }

       Swal.fire({
  title: 'Congratulations!',
html: `
    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
      <img src="assets/img/confetti-icegif.gif" width="240" height="200" />
      <img src="assets/img/OWL LOGO.png" width="120" height="100" />
    </div>
    <p style="font-size: 20px; font-weight: bold; margin-top: 10px;">You won: ${prize}</p>
  `,
  showConfirmButton: true,
  confirmButtonText: 'OK'
}).then(() => {
  reshuffleAndReset();
});

      }
    }
  }
});


  });
});

function dropBall(xPos = width/2) {
  if (balls.length > 0) return; // prevent more than 1 ball
  const radius = 18;
  const ball = Bodies.circle(xPos + (Math.random()-0.5)*20, 30, radius, {
    restitution: 0.5,
    friction: 0.02,
    frictionAir: 0.003,
    density: 0.001,
    label: 'ball' + totalBallsDropped,
   render: {
    sprite: {
      texture: 'assets/img/ball_sandy.png', // path to your icon/image
      xScale: 0.09,         // adjust size
      yScale: 0.09
    }
  }
  });
  balls.push(ball);
  World.add(engine.world, ball);
  totalBallsDropped++;
  document.getElementById('ballCountBadge').textContent = 'Balls: ' + totalBallsDropped;
}

document.getElementById('dropBtn').addEventListener('click', () => dropBall());
canvasWrap.addEventListener('click', (e) => {
  const rect = canvasWrap.getBoundingClientRect();
  const x = e.clientX - rect.left;
  dropBall(x);
});
document.getElementById('resetBtn').addEventListener('click', reshuffleAndReset);

function reshuffleAndReset() {
  balls.forEach(b => Composite.remove(engine.world, b));
  balls.length = 0;
  totalBallsDropped = 0;
  document.getElementById('ballCountBadge').textContent = 'Balls: 0';

  slotNames = shuffleArray(slotNames);
  resetLabels();
}

Engine.run(engine);
Render.run(render);

window.addEventListener('resize', () => {
  const w = canvasWrap.clientWidth;
  const h = canvasWrap.clientHeight;
  render.canvas.width = w;
  render.canvas.height = h;
});
</script>

</body>
</html>
