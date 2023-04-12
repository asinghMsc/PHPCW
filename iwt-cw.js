$(document).ready(function() {
  // Get the references to form and buttons
  const form = $('form')[0];
  const sendQuery = $('#send-query')[0];
  const clearInButton = $('#clear-input')[0];
  const clearOutButton = $('#clear-output')[0];
  const results = $('#results')[0];
  const output = $('#output')[0];

  // Click event listener to send button
  sendQuery.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent form from submitting
    console.log('sendButton clicked');
    // Form input values
    const year = form.year.value;
    const winner = form.winner.value;
    const runnerUp = form['runner-up'].value;
    const file = form.file.value;
    const yearOp = form['year-op'].value;
    const tournament = form.tournament.value;
    // URL for AJAX request to php file
    const url = `iwt-cw.php?file=${file}&year=${year}&yearOp=${yearOp}&tournament=${tournament}&winner=${winner}&runnerUp=${runnerUp}`;
    // Show loading message
    output.textContent = 'Loading your results...';
    // Send the AJAX request
    $.getJSON(url, (data) => {
      // Clear loading message
      output.textContent = '';
      results.innerHTML = '';
      // Check for any errors in response
      if (data.error) {
        output.textContent = `Error: ${data.error}`;
        return;
      }
      // Check to see if there are no results
      if (data.length === 0) {
        output.textContent = 'No results found.';
        return;
      }
      // Add the results to table
      data.forEach((result) => {
        const row = results.insertRow(-1);
        row.insertCell(0).textContent = result.year;
        row.insertCell(1).textContent = result.tournament;
        row.insertCell(2).textContent = result.winner;
        row.insertCell(3).textContent = result['runner-up'];
      });
    });
  });

  // Add a click event listener to clear input button
  clearInButton.addEventListener('click', () => {
    form.reset(); // Reset form to default values
  });

  // Add a click event listener to clear output button
  clearOutButton.addEventListener('click', () => {
    results.innerHTML = ''; // Clear table of results
    output.textContent = ''; // Clear output message
  });
});
