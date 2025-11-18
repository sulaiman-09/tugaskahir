# ApexCharts Customization Guide

## 🎨 Preset Chart Styles

### 1. Dark Mode Chart
```javascript
const options = {
    // ... existing options
    theme: {
        mode: 'dark'
    },
    grid: {
        borderColor: '#2f2f2f'
    }
};
```

### 2. Gradient Color Variations

#### Blue to Purple Gradient
```javascript
colors: ['#0d6efd'],
fill: {
    type: 'gradient',
    gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.45,
        opacityTo: 0.05,
        stops: [20, 100, 100, 100],
        colorStops: [
            {
                offset: 0,
                color: '#0d6efd',
                opacity: 0.9
            },
            {
                offset: 100,
                color: '#6f42c1',
                opacity: 0.1
            }
        ]
    }
}
```

#### Green Gradient (Growth-oriented)
```javascript
colors: ['#28a745'],
fill: {
    type: 'gradient',
    gradient: {
        shadeIntensity: 0.8,
        opacityFrom: 0.5,
        opacityTo: 0.05,
        stops: [20, 100, 100, 100]
    }
}
```

#### Red Gradient (Warning/Alert)
```javascript
colors: ['#dc3545'],
fill: {
    type: 'gradient',
    gradient: {
        shadeIntensity: 0.8,
        opacityFrom: 0.4,
        opacityTo: 0.02,
        stops: [20, 100, 100, 100]
    }
}
```

---

## 📊 Different Chart Types

### Line Chart (Minimalist)
```javascript
chart: {
    type: 'line',  // Change from 'area'
    height: 400,
},
stroke: {
    curve: 'smooth',
    width: 3
},
fill: {
    type: 'solid'  // Disable gradient
}
```

### Column/Bar Chart (Comparison)
```javascript
chart: {
    type: 'bar',
    height: 400,
},
plotOptions: {
    bar: {
        horizontal: false,
        columnWidth: '55%',
        dataLabels: {
            position: 'top'
        }
    }
}
```

### Candlestick Chart (OHLC Data)
```javascript
chart: {
    type: 'candlestick',
    height: 400,
},
plotOptions: {
    candlestick: {
        colors: {
            upward: '#28a745',
            downward: '#dc3545'
        }
    }
}
```

---

## 🎯 Interactive Features

### 1. Data Labels on Points
```javascript
dataLabels: {
    enabled: true,
    offsetY: -5,
    style: {
        fontSize: '12px',
        colors: ['#0d6efd']
    }
}
```

### 2. Markers Styling
```javascript
stroke: {
    curve: 'smooth',
    width: 2
},
markers: {
    size: 5,
    hover: {
        size: 8
    },
    colors: ['#0d6efd'],
    strokeWidth: 2,
    strokeColors: ['#ffffff'],
    shape: 'circle'
}
```

### 3. Advanced Tooltip
```javascript
tooltip: {
    enabled: true,
    theme: 'light',
    x: {
        format: 'dd MMM yyyy'
    },
    y: {
        formatter: function(value) {
            return Math.round(value) + ' customers';
        }
    },
    onDatasetHover: {
        highlightDataSeries: true
    }
}
```

### 4. Zoom Configuration
```javascript
chart: {
    toolbar: {
        tools: {
            download: true,
            selection: true,
            zoom: true,
            zoomin: true,
            zoomout: true,
            pan: true,
            reset: true
        }
    }
}
```

---

## 📱 Responsive Breakpoints

### Mobile-First Responsive
```javascript
responsive: [
    {
        breakpoint: 480,
        options: {
            chart: { height: 300 },
            xaxis: {
                labels: {
                    rotate: 90,
                    fontSize: 10
                }
            }
        }
    },
    {
        breakpoint: 768,
        options: {
            chart: { height: 350 },
            xaxis: {
                labels: {
                    rotate: 45,
                    fontSize: 11
                }
            }
        }
    },
    {
        breakpoint: 1024,
        options: {
            chart: { height: 400 },
            xaxis: {
                labels: {
                    rotate: 0,
                    fontSize: 12
                }
            }
        }
    }
]
```

---

## 🎨 Custom Colors & Styling

### Axis Labels Styling
```javascript
xaxis: {
    labels: {
        style: {
            colors: '#6c757d',
            fontSize: '13px',
            fontFamily: 'Arial, sans-serif',
            fontWeight: 500
        }
    }
},
yaxis: {
    labels: {
        style: {
            colors: '#6c757d',
            fontSize: '13px'
        }
    }
}
```

### Grid Customization
```javascript
grid: {
    show: true,
    borderColor: '#e9ecef',
    strokeDashArray: 3,
    position: 'back',
    xaxis: {
        lines: {
            show: false
        }
    },
    yaxis: {
        lines: {
            show: true
        }
    }
}
```

### Legend Positioning
```javascript
legend: {
    position: 'bottom',  // top, right, bottom, left
    offsetY: 10,
    horizontalAlign: 'center',
    fontSize: '13px',
    markers: {
        width: 12,
        height: 12
    }
}
```

---

## 📈 Advanced Data Features

### 1. Multiple Series
```javascript
series: [
    {
        name: 'New Customers',
        data: [...],
        color: '#0d6efd'
    },
    {
        name: 'Returning Customers',
        data: [...],
        color: '#28a745'
    },
    {
        name: 'Inactive',
        data: [...],
        color: '#6c757d'
    }
],
colors: ['#0d6efd', '#28a745', '#6c757d']
```

### 2. Data Point Annotations
```javascript
annotations: {
    points: [
        {
            x: new Date('2024-01-15').getTime(),
            y: 100,
            marker: {
                size: 8,
                fillColor: '#dc3545',
                strokeColor: '#fff',
                strokeWidth: 2
            },
            label: {
                borderColor: '#dc3545',
                offsetY: -10,
                text: 'Peak Sales'
            }
        }
    ]
}
```

### 3. Trend Line
```javascript
plotOptions: {
    line: {
        enableShades: true
    }
},
// Add before data, atau custom render
```

---

## 🔄 Dynamic Updates

### Update Chart dengan Smooth Animation
```javascript
// Update options
chart.updateOptions({
    xaxis: {
        categories: newLabels
    }
});

// Update series dengan animation
chart.updateSeries([{
    data: newValues
}], false);  // false = tanpa reset zoom
```

### Partial Update (hanya data)
```javascript
chart.updateSeries([
    {
        data: newValues
    }
], false);
```

### Partial Update (hanya options)
```javascript
chart.updateOptions({
    colors: ['#dc3545'],
    title: {
        text: 'Updated Chart Title'
    }
});
```

---

## 🚀 Performance Optimization

### Large Dataset Handling
```javascript
chart: {
    type: 'area',
    height: 400,
    sparkline: {
        enabled: false
    },
    // Disable unnecessary animations untuk data besar
    animations: {
        enabled: false  // atau speed: 100
    }
},
// Reduce point density
xaxis: {
    tickAmount: 10  // Max 10 ticks pada x-axis
}
```

### Lazy Loading
```javascript
// Muat chart hanya jika terlihat di viewport
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            initializeChart();
            observer.unobserve(entry.target);
        }
    });
});

observer.observe(document.querySelector('#customerGrowthChart'));
```

---

## 🎯 Specific Use Cases

### 1. Sales Performance Chart
```javascript
colors: ['#28a745'],
stroke: {
    curve: 'smooth',
    width: 3
},
fill: {
    type: 'gradient',
    gradient: {
        opacityFrom: 0.6,
        opacityTo: 0.1
    }
},
markers: {
    size: 6,
    colors: ['#28a745'],
    strokeColors: '#fff'
}
```

### 2. Minimal/Clean Chart
```javascript
chart: {
    toolbar: { show: false },
    sparkline: { enabled: false }
},
xaxis: {
    labels: { show: false },
    axisBorder: { show: false }
},
yaxis: {
    labels: { show: false }
},
grid: { show: false },
legend: { show: false }
```

### 3. Dashboard Compact Chart
```javascript
chart: {
    type: 'area',
    height: 200,  // Compact height
    toolbar: { show: false }
},
dataLabels: { enabled: false },
tooltip: {
    shared: true,
    intersect: false
}
```

---

## 📊 Export Features

### Download Chart as Image
```javascript
// Already included in toolbar, tapi bisa custom:
chart: {
    toolbar: {
        tools: {
            download: {
                csv: {
                    filename: 'customer-growth.csv'
                },
                svg: {
                    filename: 'customer-growth.svg'
                },
                png: {
                    filename: 'customer-growth.png'
                }
            }
        }
    }
}
```

### Export Data to CSV
```javascript
function exportDataToCSV() {
    const csv = [
        ['Period', 'Customers'],
        ...chartData.labels.map((label, idx) => [label, chartData.values[idx]])
    ].map(row => row.join(',')).join('\n');

    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'customer-growth.csv';
    a.click();
}
```

---

## 🔗 Integration Examples

### Dengan Bootstrap Cards
```html
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Customer Growth</h5>
    </div>
    <div class="card-body">
        <div id="customerGrowthChart"></div>
    </div>
    <div class="card-footer bg-light">
        <small class="text-muted">Last updated: {{ now()->format('Y-m-d H:i') }}</small>
    </div>
</div>
```

### Dengan Modal
```javascript
// Buka chart di modal
const modal = new bootstrap.Modal(document.getElementById('chartModal'));
initializeChart();  // Di dalam modal body
modal.show();
```

### Dengan Tabs
```html
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" href="#monthly">Monthly</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#yearly">Yearly</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="monthly">
        <div id="monthlyChart"></div>
    </div>
</div>
```

---

## 🔐 Security Considerations

### Sanitize Data Labels
```javascript
xaxis: {
    categories: data.labels.map(label => {
        return DOMPurify.sanitize(label);  // Prevent XSS
    })
}
```

### Validate Data Before Rendering
```javascript
function updateChart(data) {
    if (!Array.isArray(data.labels) || !Array.isArray(data.values)) {
        console.error('Invalid data format');
        return;
    }
    // Proceed with update
}
```

---

## 📚 Useful Resources

- [ApexCharts Official Docs](https://apexcharts.com/docs/)
- [ApexCharts React Components](https://apexcharts.com/docs/react/)
- [ApexCharts Vue Components](https://apexcharts.com/docs/vue/)
- [Chart.js Alternative](https://www.chartjs.org/)
- [D3.js for Advanced Visualization](https://d3js.org/)

---

**Happy Charting! 📊**
