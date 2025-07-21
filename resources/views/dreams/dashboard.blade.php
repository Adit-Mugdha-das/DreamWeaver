<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Trends Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Vanta.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>

<style>
  html, body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', sans-serif;
    background-color: #0a0c1b;
    color: white;
    min-height: 100vh;
    overflow-x: hidden;
  }

  #vanta-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
  }

  .content {
    position: relative;
    z-index: 1;
    padding: 2rem;
  }

  h1, h2 {
    color: #c084fc;
  }

  .chart-box {
  position: relative;
  background: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(255, 255, 255, 0.08);
  padding: 2rem;
  border-radius: 1.5rem;
  backdrop-filter: blur(3px);
  box-shadow:
    0 0 10px rgba(192, 132, 252, 0.22),
    0 0 18px rgba(192, 132, 252, 0.12);
  transition: box-shadow 0.3s ease;
}

.chart-box:hover {
  box-shadow:
    0 0 14px rgba(192, 132, 252, 0.4),
    0 0 24px rgba(192, 132, 252, 0.25);
}



  .chart-canvas {
    max-height: 300px;
    width: 100%;
  }

  

  .keywords span {
  background-color: #c084fc44;
  padding: 0.4rem 0.8rem;
  border-radius: 9999px;
  font-size: 0.85rem;
  margin: 0.3rem;
  display: inline-block;
  color: white;
  transition: all 0.3s ease;
  text-shadow: none;
}

.keywords span:hover {
  text-shadow:
    0 0 4px #c084fc,
    0 0 8px #c084fc,
    0 0 12px #c084fc,
    0 0 16px #c084fc; /* Neon purple glow */
  color: #ffffff;
}



  .grid {
    display: grid;
    gap: 2rem;
  }

  @media(min-width: 768px) {
    .grid {
      grid-template-columns: 1fr 1fr;
    }
  }

  .back-button {
  margin-top: 2rem;
  display: inline-block;
  padding: 0.6rem 1.2rem;
  background-color: rgba(255, 255, 255, 0.08); /* normal transparent black */
  border: 1px solid rgba(255, 255, 255, 0.15);
  color: white;
  border-radius: 0.75rem;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
}

.back-button:hover {
  background-color: rgba(255, 255, 255, 0.02); /* darker on hover */
  border-color: rgba(255, 255, 255, 0.08);
  transform: scale(1.03);
}



    @keyframes fadeInSlide {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.content {
  position: relative;
  z-index: 1;
  padding: 2rem;
  animation: fadeInSlide 1s ease-out both;
}

.download-btn {
  position: absolute;
  bottom: 2px;
  right: 2px;
  padding: 0.4rem 0.8rem;
  background-color: rgba(255, 255, 255, 0.06);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: white;
  font-size: 0.85rem;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 2;
}

.download-btn:hover {
  background-color: rgba(255, 255, 255, 0.12);
  transform: scale(1.05);
}

</style>

</head>
<body>

<!-- Vanta Background -->
<div id="vanta-bg"></div>

<!-- Page Content -->
<div class="content">
  <h1 class="text-3xl font-bold mb-8 text-center">Dream Patterns</h1>

  <div class="grid">
  <!-- Emotion Pie Chart -->
  <div class="chart-box">
    <h2 class="text-lg mb-2 text-center">Emotion Distribution</h2>
    <div style="height: 300px; position: relative;">
      <canvas id="emotionChart" class="chart-canvas"></canvas>
      <button onclick="downloadChart('emotionChart')" class="download-btn"> Download Chart</button>
    </div>
  </div>

  <!-- Weekly Line Chart -->
  <div class="chart-box">
    <h2 class="text-lg mb-2 text-center">Dreams Per Day</h2>
    <div style="height: 300px; position: relative; padding-bottom: 2rem;">

      <canvas id="dailyChart" class="chart-canvas"></canvas>
      <button onclick="downloadChart('dailyChart')" class="download-btn">📥 Download Chart</button>

    </div>
  </div>
</div> <!-- ✅ This is the correct place to close .grid -->


  <!-- Keywords -->
  <div class="chart-box mt-8">
    <h2 class="text-lg mb-4 text-center">🧠 Top Keywords in Your Dreams</h2>
    <div class="keywords text-center">
      @foreach($topKeywords as $word => $count)
        <span>{{ $word }} ({{ $count }})</span>
      @endforeach
    </div>
  </div>

  <!-- Back Button -->
  <div class="text-center">
    <a href="{{ route('welcome') }}" class="back-button">⬅ Back to Home</a>
  </div>
</div>

<!-- Chart Rendering -->
<script>
  const emotionChart = new Chart(document.getElementById('emotionChart'), {
  type: 'pie',
  data: {
    labels: {!! json_encode($emotionCounts->keys()) !!},
    datasets: [{
      data: {!! json_encode($emotionCounts->values()) !!},
      backgroundColor: ['#6366f1', '#8b5cf6', '#0ea5e9', '#facc15', '#14b8a6', '#f472b6'],
      borderColor: '#0f172a',
      borderWidth: 2,
      hoverOffset: 10
    }]
  },
  options: {
    plugins: {
      legend: {
        labels: { color: 'white', padding: 20 }
      }
    },
    animation: {
      animateScale: true,
      duration: 1200,
      easing: 'easeOutQuart'
    }
  }
});


const ctx = document.getElementById('dailyChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(20, 184, 166, 0.4)');
gradient.addColorStop(1, 'rgba(20, 184, 166, 0)');

const dailyChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: {!! json_encode($dailyCounts->keys()) !!},
    datasets: [{
      label: 'Dreams per Day',
      data: {!! json_encode($dailyCounts->values()) !!},
      borderColor: '#14b8a6',
      backgroundColor: gradient,
      tension: 0.4,
      fill: true,
      pointBackgroundColor: '#0d9488',
      pointBorderColor: '#0f172a',
      pointRadius: 5,
      pointHoverRadius: 7,
      borderWidth: 2.5,
    }]
  },
  options: {
    scales: {
      x: {
        ticks: { color: 'white' },
        grid: { color: 'rgba(255,255,255,0.05)' }
      },
      y: {
        ticks: { color: 'white' },
        grid: { color: 'rgba(255,255,255,0.05)' }
      }
    },
    plugins: {
      legend: {
        labels: { color: 'white' }
      }
    },
    animation: {
      tension: {
        duration: 1200,
        easing: 'easeOutQuart',
        from: 0.1,
        to: 0.4,
        loop: false
      }
    }
  }
});


  VANTA.NET({
    el: "#vanta-bg",
    mouseControls: true,
    touchControls: true,
    gyroControls: false,
    minHeight: 200.00,
    minWidth: 200.00,
    scale: 1.00,
    scaleMobile: 1.00,
    color: 0x8e44ad,
    backgroundColor: 0x0a0c1b,
    points: 12.0,
    maxDistance: 20.0,
    spacing: 15.0
  });


  function downloadChart(chartId) {
  const chartCanvas = document.getElementById(chartId);
  const link = document.createElement('a');
  link.download = `${chartId}.png`;
  link.href = chartCanvas.toDataURL('image/png');
  link.click();
}

</script>

</body>
</html>
