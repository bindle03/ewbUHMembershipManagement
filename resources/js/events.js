// Add scroll functionality for the table
const tableWrapper = document.querySelector('.table-wrapper');
if (tableWrapper.scrollHeight > tableWrapper.clientHeight) {
  tableWrapper.classList.add('vertical-scrollable');
}
if (tableWrapper.scrollWidth > tableWrapper.clientWidth) {
  tableWrapper.classList.add('horizontal-scrollable');
}
