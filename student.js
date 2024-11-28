document.addEventListener("DOMContentLoaded", () => {
  const studentTable = document.querySelector("#studentTable tbody");
  const studentModal = document.getElementById("studentModal");
  const modalTitle = document.getElementById("modalTitle");
  const studentIDInput = document.getElementById("studentID");
  const studentNameInput = document.getElementById("studentName");
  const studentEmailInput = document.getElementById("studentEmail");
  const studentCourseSelect = document.getElementById("studentCourse"); // Updated
  const studentYearInput = document.getElementById("studentYear");
  const studentSectionInput = document.getElementById("studentSection");
  const saveStudentBtn = document.getElementById("saveStudentBtn");
  const closeModalBtn = document.getElementById("closeModalBtn");
  const addStudentBtn = document.getElementById("addStudentBtn");

  let students = [];
  let editIndex = null;

  function showModal(editMode = false, index = null) {
      if (editMode) {
          modalTitle.textContent = "Edit Student";
          const student = students[index];
          studentIDInput.value = student.studentID;
          studentNameInput.value = student.name;
          studentEmailInput.value = student.email;
          studentCourseSelect.value = student.course; // Updated
          studentYearInput.value = student.year;
          studentSectionInput.value = student.section;
          editIndex = index;
      } else {
          modalTitle.textContent = "Add Student";
          studentIDInput.value = "";
          studentNameInput.value = "";
          studentEmailInput.value = "";
          studentCourseSelect.value = "CS"; // Default selection
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
      const course = studentCourseSelect.value; // Updated
      const year = studentYearInput.value.trim();
      const section = studentSectionInput.value.trim();

      if (!studentID || !name || !email || !course || !year || !section) {
          alert("Please fill out all fields!");
          return;
      }

      const student = { studentID, name, email, course, year, section };

      if (editIndex !== null) {
          students[editIndex] = student;  // Update existing student
      } else {
          students.push(student);  // Add new student
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
                  <button onclick="showModal(true, ${index})">Edit</button>
                  <button onclick="deleteStudent(${index})">Delete</button>
              </td>
          `;
          studentTable.appendChild(row);
      });
  }

  addStudentBtn.addEventListener("click", () => showModal(false));
  closeModalBtn.addEventListener("click", closeModal);
  saveStudentBtn.addEventListener("click", saveStudent);
});
