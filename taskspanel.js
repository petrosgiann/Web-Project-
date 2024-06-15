// taskspanel.js

document.addEventListener("DOMContentLoaded", function () {
    // Καλέστε τη συνάρτηση για να ανακτήσετε τα assigned tasks κατά τη φόρτωση της σελίδας
    getAssignedTasks();
  });
  
  function getAssignedTasks() {
    
    fetch("gettasks.php")
      .then((response) => response.json())
      .then((data) => {
        
        displayAssignedTasks(data);
      })
      .catch((error) => console.error("Error fetching assigned tasks:", error));
  }
  
  function displayAssignedTasks(tasks) {
    const tasksList = document.getElementById("tasks-list");
  
  
    // Δείξτε τα assigned tasks
    tasks.forEach((task) => {
      const taskElement = document.createElement("li");
      taskElement.classList.add("task");
      taskElement.id = `task-${task.task_id}`;
      taskElement.innerHTML = `
      <h3> Task Details</h3>
      <p><span>Name:</span><br> ${task.citizen_name}</p>
      <p><span>Phone:</span><br> ${task.citizen_phone}</p>
      <p><span>Date:</span><br> ${task.date_submitted}</p>
      <p><span>Type:</span><br> ${task.task_type}</p>
      <p><span>Product:</span><br> ${task.product_name}</p>
      <p><span>Quantity:</span><br> ${task.quantity}</p>
      <div class="taskbuttons">
      <button class="complete-button" onclick="completeTask('${task.task_id}')">Complete</button>
      <button class="abandon-button" onclick="abandonTask('${task.task_id}')">Abandon</button> </div>
      `;
      tasksList.appendChild(taskElement);
    });
  }


  function abandonTask(taskId) {
    // Δημιουργία αντικειμένου XMLHttpRequest
    var xhr = new XMLHttpRequest();
  
    // Ορισμός της μεθόδου και του URL
    xhr.open("GET", `abandontask.php?taskId=${taskId}`, true);
  
    // Καθορισμός λειτουργιών για το όταν ολοκληρωθεί η αίτηση
    xhr.onload = function () {
      if (xhr.status == 200) {
        // Εδώ μπορείτε να ενημερώσετε το UI ή να κάνετε άλλες ενέργειες ανάλογα με την απάντηση από τον server.
        console.log('Task abandoned:', xhr.responseText);
        // Ανανέωση της λίστας με τα tasks μετά την εγκατάλειψη του task
        
        removeTaskFromList(taskId);
        location.reload();
       
      } else {
        console.error('Error abandoning task. Status:', xhr.status);
      }
    };
  
    // Αποστολή της αίτησης
    xhr.send();
  }

  function completeTask(taskId) {
    // Δημιουργία αντικειμένου XMLHttpRequest
    var xhr = new XMLHttpRequest();
  
    // Ορισμός της μεθόδου και του URL
    xhr.open("GET", `completetask.php?taskId=${taskId}`, true);
  
    // Καθορισμός λειτουργιών για το όταν ολοκληρωθεί η αίτηση
    xhr.onload = function () {
      if (xhr.status == 200) {
        // Εδώ μπορείτε να ενημερώσετε το UI ή να κάνετε άλλες ενέργειες ανάλογα με την απάντηση από τον server.
        console.log('Task marked as completed:', xhr.responseText);
        // Ανανέωση της λίστας με τα tasks μετά την ολοκλήρωση του task
        updateTruckLoad(taskId);
        removeTaskFromList(taskId);
        location.reload();
        
        
        
      } else {
        console.error('Error marking task as completed. Status:', xhr.status);
      }
    };
  
    // Αποστολή της αίτησης
    xhr.send();
  }


  function removeTaskFromList(taskId) {
    // Εύρεση του task στο UI με βάση το taskId
    const taskElement = document.getElementById(`task-${taskId}`);

    // Αν το task υπάρχει, τότε αφαιρείται
    if (taskElement) {
        taskElement.remove();
    }
}
  
  
function updateTruckLoad(taskId) {
  var xhr = new XMLHttpRequest();

  xhr.open("GET", `updatetruckload.php?taskId=${taskId}`, true);

  xhr.onload = function () {
    if (xhr.status == 200) {
     
      console.log('Truck updated successfully:', xhr.responseText);
    } else {
      console.error('Error updating truck . Status:', xhr.status);
    }
  };

  xhr.send();
}

