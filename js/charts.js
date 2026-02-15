fetch('php/get_hole_data.php')
  .then(res => res.json())
  .then(data => {
    const ids = [];
    const planned = [], design = [], actual = [];

    data.forEach(fc => {
      const props = fc.features[0].properties;
      if (!ids.includes(props.hole_id)) {
        ids.push(props.hole_id);
        planned.push(props.planned_elevation);
        design.push(props.design_elevation);
        actual.push(props.actual_elevation);
      }
    });

    new Chart(document.getElementById('holeChart'), {
      type: 'line',
      data: {
        labels: ids,
        datasets: [
          { label: 'Planned', data: planned, borderColor: 'green', fill: false },
          { label: 'Design', data: design, borderColor: 'blue', fill: false },
          { label: 'Actual', data: actual, borderColor: 'red', fill: false }
        ]
      }
    });
  });