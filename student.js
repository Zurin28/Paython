document.addEventListener("DOMContentLoaded", () => {
  // Elements for student management
  const studentTable = document.querySelector("#studentTable tbody");
  const studentModal = document.getElementById("studentModal");
  const modalTitle = document.getElementById("modalTitle");
  const studentIDInput = document.getElementById("studentID");
  const studentNameInput = document.getElementById("studentName");
  const studentEmailInput = document.getElementById("studentEmail");
  const studentCourseSelect = document.getElementById("studentCourse");
  const studentYearInput = document.getElementById("studentYear");
  const studentSectionInput = document.getElementById("studentSection");
  const saveStudentBtn = document.getElementById("saveStudentBtn");
  const closeModalBtn = document.getElementById("closeModalBtn");
  const addStudentBtn = document.getElementById("addStudentBtn");

  // Elements for fee management
  const showFeesModal = document.getElementById("show-fees-modal");
  const feesTableBody = document.querySelector("#feesTable tbody");
  const closeFeesModalButton = document.getElementById("closeFeesModalButton");

  let students = [];
  let editIndex = null;

  // Student Management Functions
  function showModal(editMode = false, index = null) {
      if (editMode) {
          modalTitle.textContent = "Edit Student";
          const student = students[index];
          studentIDInput.value = student.studentID;
          studentNameInput.value = student.name;
          studentEmailInput.value = student.email;
          studentCourseSelect.value = student.course;
          studentYearInput.value = student.year;
          studentSectionInput.value = student.section;
          editIndex = index;
      } else {
          modalTitle.textContent = "Add Student";
          studentIDInput.value = "";
          studentNameInput.value = "";
          studentEmailInput.value = "";
          studentCourseSelect.value = "CS";
          studentYearInput.value = "";
          studentSectionInput.value = "";
          editIndex = null;
      }
      studentModal.style.display = "block";
  }

  function closeModal() {
      studentModal.style.display = "none";
  }

  function saveStudent() {
      const studentID = studentIDInput.value.trim();
      const name = studentNameInput.value.trim();
      const email = studentEmailInput.value.trim();
      const course = studentCourseSelect.value;
      const year = studentYearInput.value.trim();
      const section = studentSectionInput.value.trim();

      if (!studentID || !name || !email || !course || !year || !section) {
          alert("Please fill out all fields!");
          return;
      }

      const student = { studentID, name, email, course, year, section };

      if (editIndex !== null) {
          students[editIndex] = student;
      } else {
          students.push(student);
      }

      renderTable();
      closeModal();
  }

  function deleteStudent(index) {
      students.splice(index, 1);
      renderTable();
  }

  function renderTable() {
      studentTable.innerHTML = "";
      students.forEach((student, index) => {
          const row = document.createElement("tr");
          row.innerHTML = `
              <td>${index + 1}</td>
              <td>${student.studentID}</td>
              <td>${student.name}</td>
              <td>${student.email}</td>
              <td>${student.course}</td>
              <td>${student.year}</td>
              <td>${student.section}</td>
              <td>
                  <button class="view-status-btn" data-student-id="${student.studentID}">View Status</button>
                  <button onclick="deleteStudent(${index})">Delete</button>
              </td>
          `;
          studentTable.appendChild(row);
      });
  }

  // Fee Management Functions
  function populateFeesTable(data) {
      feesTableBody.innerHTML = ""; // Clear existing rows
      
      // Add academic period header if available
      if (data.academic_period) {
          const headerRow = document.createElement("tr");
          headerRow.innerHTML = `
              <td colspan="3" class="academic-period-header">
                  ${data.academic_period.school_year} - ${data.academic_period.semester} Semester
              </td>
          `;
          feesTableBody.appendChild(headerRow);
      }

      if (data.fees && data.fees.length > 0) {
          data.fees.forEach((fee, index) => {
              const row = document.createElement("tr");
              row.innerHTML = `
                  <td>${index + 1}</td>
                  <td>${fee.FeeName}</td>
                  <td>${fee.paymentStatus}</td>
              `;
              feesTableBody.appendChild(row);
          });
      } else {
          // If no data is available, display a message
          const row = document.createElement("tr");
          row.innerHTML = `<td colspan="3">No fees data found for this period</td>`;
          feesTableBody.appendChild(row);
      }
  }

  // Event delegation to handle clicks on all "View Status" buttons
  studentTable.addEventListener("click", (event) => {
      if (event.target && event.target.classList.contains("view-status-btn")) {
          const studentId = event.target.dataset.studentId;

          // Show the fees modal
          showFeesModal.style.display = "block";

          // Fetch current academic period and fees data
          fetch('get_current_period.php')
              .then(response => response.json())
              .then(period => {
                  if (!period.school_year || !period.semester) {
                      alert('No active academic period set.');
                      return;
                  }

                  // Fetch the fees data with academic period
                  return fetch(`fetch_fees.php?student_id=${studentId}&school_year=${period.school_year}&semester=${period.semester}`);
              })
              .then(response => response.json())
              .then(data => {
                  populateFeesTable(data);
              })
              .catch((error) => {
                  console.error("Error fetching data:", error);
                  feesTableBody.innerHTML = `<tr><td colspan="3">Error loading fee data</td></tr>`;
              });
      }
  });

  // Close the Fees Status Modal
  closeFeesModalButton.addEventListener("click", () => {
      showFeesModal.style.display = "none";  // Hide the modal
  });

  // Event Listeners for adding/editing students
  addStudentBtn.addEventListener("click", () => showModal(false));  // Open modal to add a new student
  closeModalBtn.addEventListener("click", closeModal);  // Close the student modal
  saveStudentBtn.addEventListener("click", saveStudent);  // Save student data

  // Initial Render (Optional if you want to load pre-existing students)
  renderTable();
});
