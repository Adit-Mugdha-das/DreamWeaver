<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mindfulness & Sleep Audio</title>
  @vite('resources/css/app.css')
  <style>
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: 'Inter', sans-serif;
    background-color: #000; /* Deep black for a true dark mode */
    color: white;
    overflow-x: hidden;
  }

  #vanta-bg {
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: -1;
    top: 0;
    left: 0;
  }

  .container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    text-align: center;
    position: relative;
    z-index: 1;
  }

  .audio-card {
    background: rgba(255, 255, 255, 0.04); /* subtle dark glass effect */
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 0 15px rgba(168, 85, 247, 0.3); /* soft purple glow */
    transition: transform 0.3s ease;
  }

  .audio-card:hover {
    transform: scale(1.02);
  }

  iframe {
    width: 100%;
    height: 315px;
    border: none;
    border-radius: 0.5rem;
  }

  .nav-button {
    position: fixed;
    top: 1rem;
    left: 1rem;
    z-index: 10;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    background-color: rgba(0, 0, 0, 0.75);
    border: 1px solid #a855f7;
    color: white;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  .nav-button:hover {
    background-color: rgba(168, 85, 247, 0.8);
    color: black;
  }
.nav-button {
  position: fixed;
  top: 1rem;
  left: 1rem;
  z-index: 10;
  padding: 0.3rem 0.75rem;
  font-size: 0.9rem;
  border-radius: 9999px;
  background-color: rgba(0, 0, 0, 0.7);
  border: 1px solid #a855f7;
  color: white;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
}


</style>


</head>
<body>
<!-- Background -->
<!-- Home Button (Top Left) -->
<a href="{{ route('welcome') }}" class="nav-button">← Home</a>




  <div class="container">
    <h1 class="text-3xl font-bold text-purple-300 mb-4">🧘 Guided Mindfulness & Sleep Audio</h1>
    <p class="mb-8 text-purple-200">Listen to these to reduce anxiety or fall asleep peacefully after a dream.</p>

    <div class="audio-card">
      <h2 class="text-xl font-semibold mb-2">🌙 Sleep Meditation</h2>
      <iframe src="https://www.youtube.com/embed/ZToicYcHIOU" allowfullscreen></iframe>
    </div>

    <div class="audio-card">
      <h2 class="text-xl font-semibold mb-2">💤 Deep Sleep Music</h2>
      <iframe src="https://www.youtube.com/embed/1ZYbU82GVz4" allowfullscreen></iframe>


    </div>

    <div class="audio-card">
      <h2 class="text-xl font-semibold mb-2">🧘‍♀️ Anxiety Relief</h2>
      <iframe src="https://www.youtube.com/embed/W19PdslW7iw" allowfullscreen></iframe>
    </div>

<div class="audio-card">
  <h2 class="text-xl font-semibold mb-2">🌲 Forest Night with Crickets & Wind</h2>
  <iframe src="https://www.youtube.com/embed/3TNK916Pjto" allowfullscreen></iframe>
</div>
   

<div class="audio-card">
  <h2 class="text-xl font-semibold mb-2">❄️ Snowstorm Ambience with Howling Wind</h2>
  <iframe width="560" height="315" src="https://www.youtube.com/embed/ZYAO6PtW8GU" title="Snowstorm Ambience | Winter's Blizzard & Wind Sound for Sleeping" frameborder="0" allowfullscreen></iframe>
</div>


<div class="audio-card">
  <h2 class="text-xl font-semibold mb-2">🪐 Space Meditation – Binaural & Ambient</h2>
  <iframe src="https://www.youtube.com/embed/DVHaSmW9QNA" allowfullscreen></iframe>
</div>

<div class="audio-card">
  <h2 class="text-xl font-semibold mb-2">🔥 Cozy Winter Ambience with Fireplace & Blizzard Sounds</h2>
  <iframe width="560" height="315" src="https://www.youtube.com/embed/OP6_VTvSuqI" title="Cozy Winter Ambience - Crackling Fireplace, Blizzard Sounds" frameborder="0" allowfullscreen></iframe>
</div>


 <div class="audio-card">
  <h2 class="text-xl font-semibold mb-2">🌧️ Rain & Thunder for Deep Sleep</h2>
  <iframe src="https://www.youtube.com/embed/jX6kn9_U8qk" allowfullscreen></iframe>
</div>

<div class="audio-card">
  <h2 class="text-xl font-semibold mb-2">🌌 Cosmic Sleep Journey</h2>
  <iframe width="560" height="315" src="https://www.youtube.com/embed/dCrEon5ojJU" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>

<div class="audio-card">
  <h2 class="text-xl font-semibold mb-2">🌊 Ocean Waves for Sleep</h2>
  <iframe 
    src="https://www.youtube.com/embed/vPhg6sc1Mk4" 
    frameborder="0" 
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
    allowfullscreen>
  </iframe>
</div>

<div class="audio-card">
  <h2 class="text-xl font-semibold mb-2">🔥 Crackling Campfire Sounds</h2>
  <iframe src="https://www.youtube.com/embed/eyU3bRy2x44" allowfullscreen></iframe>
</div>



<div class="audio-card"> <h2 class="text-xl font-semibold mb-2">❄️ Snowfall Ambience & Wind</h2> <iframe src="https://www.youtube.com/embed/q76bMs-NwRk" allowfullscreen></iframe> </div>



<div class="audio-card">
  <h2 class="text-xl font-semibold mb-2">🎵 Healing Frequencies for Deep Sleep</h2>
  <iframe src="https://www.youtube.com/embed/o-9T184mpY4" allowfullscreen></iframe>
</div>



  </div>
  
</body>
</html>
