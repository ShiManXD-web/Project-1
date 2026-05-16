const ctx = document.getElementById('myChart');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [
      'PHP','JavaScript','HTML/CSS','Python',
      'SQL/MySQL','Google Colab','Pandas',
      'Matplotlib','Scikit-learn','Bootstrap','AdminLTE'
    ],
    datasets: [{
      label: 'Skill Level',
      data: [3, 4, 4, 3, 4, 1, 2, 2, 1, 1, 1],
      backgroundColor: [
        '#ff6b6b','#feca57','#48dbfb','#1dd1a1',
        '#5f27cd','#00d2d3','#54a0ff','#a29bfe',
        '#f368e0','#ff9ff3','#ff6b6b'
      ],
      borderRadius: 8
    }]
  },
  options: {
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { color: "#fff" }
      },
      x: {
        ticks: { color: "#fff" }
      }
   }
}
});
// Skills Bar Chart
const skillsCtx = document.getElementById('skillsChart').getContext('2d');

new Chart(skillsCtx, {
    type: 'bar',
    data: {
        labels: ['HTML', 'CSS/Tailwind', 'JavaScript', 'PHP', 'MySQL', 'Python', 'Pandas', 'Matplotlib'],
        datasets: [{
            label: 'Proficiency (%)',
            data: [90, 75, 40, 70, 65, 30, 20, 15],
            backgroundColor: [
                '#e879f9','#d946ef','#c084fc','#93c5fd','#60a5fa','#34d399','#fbbf24','#fb7185'
            ]
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Project Pie Chart
const projectCtx = document.getElementById('projectChart').getContext('2d');

new Chart(projectCtx, {
    type: 'pie',
    data: {
        labels: ['Web Apps','Games','Data Analysis','Dashboards','School Projects'],
        datasets: [{
            data: [40, 10, 15, 15, 20],
            backgroundColor: [
                '#f472b6','#fb7185','#60a5fa','#34d399','#fbbf24'
            ]
        }]
    },
    options: {
        responsive: true
    }
});