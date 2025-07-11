const tenureSlider = document.getElementById('tenureSlider');
  const tenureLabel = document.querySelectorAll('.tenureLabel');

  // Define possible values
  const tenureValues = [2, 6, 12, 24];

  // Initialize label
  if(tenureSlider && tenureLabel){
  tenureLabel[0].innerText = `${tenureValues[tenureSlider.value]} Months`;
  tenureLabel[1].innerText = `${tenureValues[tenureSlider.value]}`;
  // On slider move
  tenureSlider.addEventListener('input', function() {
    const selectedTenure = tenureValues[this.value];
    tenureLabel[0].innerText = `${selectedTenure} Months`;
tenureLabel[1].value=`${selectedTenure}`; 
    // Optional: you can store the actual value in a hidden input for submission
    // document.getElementById('actualTenure').value = selectedTenure;
  });
}


  const request_loan = document.querySelector("#request_loan");
if(request_loan){
request_loan.addEventListener("submit", (e) => {
  e.preventDefault();
  if (!request_loan.checkValidity()) {
    request_loan.classList.add('was-validated');
    return; 
  }
  $.ajax({
    url: "post_loan",
    method: "post",
    dataType:"json",
    data: $("#request_loan").serialize(),
    beforeSend:()=>{
        $("#loadingbutton").prop("disabled", true).text("Please wait...");
    },
    complete:()=>{
         $("#loadingbutton").prop("disabled", false).text("Submit");
    },
    success: (response) => {
      
      if(response.status===1){
        request_loan.reset();
      request_loan.classList.remove('was-validated');
      alert("Your Application Has Been Submitted Successfully");
      location.href="index"
        }
        else if(response.status===-2){
      location.href="request_login"
        }
        else {
               request_loan.reset();
      request_loan.classList.remove('was-validated');
      alert("Server error")
    }
    document.querySelector(".btn-close").click();
    document.getElementById("tenureLabel").textContent = "6 Months"; 
    },
    error: function (xhr, status, error) {
      let msg = "Something went wrong. Please try again.";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }
      $('#errorBox').text(msg).show();
    }
  });
});
}



// signup
const register_user=document.querySelector("#register_user")
if(register_user){
register_user.addEventListener("submit",(e)=>{
    e.preventDefault()
    if (!register_user.checkValidity()) {
    register_user.classList.add('was-validated');
    return; 
  }

  $.ajax({
    url: "post_signup",
    method: "post",
    dataType:"json",
    data: $("#register_user").serialize(),
    beforeSend:()=>{
        $("#loadingbutton").prop("disabled", true).text("Please wait...");
    },
    complete:()=>{
         $("#loadingbutton").prop("disabled", false).text("Submit");
    },
    success: (response) => {
        if(response.status===1){
            location.href="request_otp?cgid="+window.btoa(window.btoa(response.email))
        }
        else if(response.status===-1){
      register_user.reset();
      register_user.classList.remove('was-validated');
      alert("Email Already Existed")
        }
        else if(response.status===0){
               register_user.reset();
      register_user.classList.remove('was-validated');
      alert("Server error")
        }
        else if(response.status===-2){
            location.href="index"
        }
    },
    error: function (xhr, status, error) {
      let msg = "Something went wrong. Please try again.";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }
      $('#errorBox').text(msg).show();
    }
  });
})
}

// otp
const request_otp=document.querySelector("#request_otp")
if(request_otp){
request_otp.addEventListener("submit",(e)=>{
    e.preventDefault()
    if (!request_otp.checkValidity()) {
    request_otp.classList.add('was-validated');
    return; 
  }
  let formData = $("#request_otp").serializeArray();


  $.ajax({
    url: "post_otp",
    method: "post",
    dataType:"json",
    data: formData,
    beforeSend:()=>{
        $("#loadingbutton").prop("disabled", true).text("Please wait...");
    },
    complete:()=>{
         $("#loadingbutton").prop("disabled", false).text("Verify");
    },
    success: (response) => {
        if(response.status===1){
            location.href="request_login"
        }
        else if(response.status===2){
      request_otp.reset();
      request_otp.classList.remove('was-validated');
      alert("Account Not Found")
        }
        else if(response.status===-1){
      request_otp.reset();
      request_otp.classList.remove('was-validated');
      alert("OTP is invalid")
        }
        else if(response.status===-2){
            location.href="index"
        }
    },
    error: function (xhr, status, error) {
      let msg = "Something went wrong. Please try again.";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }
      $('#errorBox').text(msg).show();
    }
  });
})
}


// login
const login_user=document.querySelector("#login_user")
if(login_user){
login_user.addEventListener("submit",(e)=>{
    e.preventDefault()
    if (!login_user.checkValidity()) {
    login_user.classList.add('was-validated');
    return; 
  }

  $.ajax({
    url: "post_login",
    method: "post",
    dataType:"json",
    data: $("#login_user").serialize(),
    beforeSend:()=>{
        $("#loadingbutton").prop("disabled", true).text("Please wait...");
    },
    complete:()=>{
         $("#loadingbutton").prop("disabled", false).text("Submit");
    },
    success: (response) => {
        if(response.status===1){
            location.href="index"
        }
        else if(response.status===-1){
      login_user.reset();
      login_user.classList.remove('was-validated');
      alert("Incorrect Password")
        }
        else if(response.status===-3){
      login_user.reset();
      login_user.classList.remove('was-validated');
      alert("Incorrect Email Address")
        }
        else if(response.status===-2){
            location.href="index"
        }
    },
    error: function (xhr, status, error) {
      let msg = "Something went wrong. Please try again.";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }
      $('#errorBox').text(msg).show();
    }
  });
})
}



let refresh_modal1=document.querySelector("#applyloan")
if(refresh_modal1){
refresh_modal1.addEventListener("click",(e)=>{
  e.preventDefault()
  $.ajax({
    url:"refresh_modal1",
    method:"get",
    dataType:"json",
    success:(response)=>{
     if (response.status === "approve") {
  document.querySelectorAll(".approve")[0]?.classList.remove("d-none");
  document.querySelectorAll(".approve")[1]?.classList.remove("d-none");
  document.querySelector(".pending")?.classList.add("d-none");
  document.querySelectorAll(".rejected")[0]?.classList.add("d-none");
  document.querySelectorAll(".rejected")[1]?.classList.add("d-none");
  if(response.amount){
    document.querySelectorAll(".get_repayment_amount")[0].innerText=response.amount
    document.querySelectorAll(".get_repayment_amount")[1].value=response.amount
  }
  else{
    console.log(response.amount)
  }
}

else if (response.status === "pending") {
  document.querySelector(".pending")?.classList.remove("d-none");
  document.querySelectorAll(".approve")[0]?.classList.add("d-none");
  document.querySelectorAll(".approve")[1]?.classList.add("d-none");
  document.querySelectorAll(".rejected")[0]?.classList.add("d-none");
  document.querySelectorAll(".rejected")[1]?.classList.add("d-none");
}

else if (response.status === "rejected") {
  document.querySelectorAll(".rejected")[0]?.classList.remove("d-none");
  document.querySelectorAll(".rejected")[1]?.classList.remove("d-none");
  document.querySelectorAll(".approve")[0]?.classList.add("d-none");
  document.querySelectorAll(".approve")[1]?.classList.add("d-none");
  document.querySelector(".pending")?.classList.add("d-none");
}
else if(response.status === "no_loan"){
  document.querySelectorAll(".rejected")[0]?.classList.remove("d-none");
  document.querySelectorAll(".rejected")[1]?.classList.remove("d-none");
  document.querySelectorAll(".approve")[0]?.classList.add("d-none");
  document.querySelectorAll(".approve")[1]?.classList.add("d-none");
  document.querySelector(".pending")?.classList.add("d-none");
}

else {
  location.href = "request_login";
}

     
    }
  })
})
}

let refresh_modal2=document.querySelector("#check_status")
if(refresh_modal2){
refresh_modal2.addEventListener("click",(e)=>{
  e.preventDefault()
  $.ajax({
    url:"refresh_modal2",
    method:"get",
    dataType:"json",
    success:(response)=>{
      if(response.status=="approve"){
        document.querySelector("#checkloanstatus").innerText="Approved"
      }
      else if(response.status=="pending"){
        document.querySelector("#checkloanstatus").innerText="Pending"
      }
      else if(response.status=="rejected"){
        document.querySelector("#checkloanstatus").innerText="Rejected"
      }
      else if(response.status=="no_loan"){
        document.querySelector("#checkloanstatus").innerText="No Loans"
      }
      else{
        location.href="request_login"
      }
    }
  })
})
}


const repayment=document.querySelector("#repayment")
if(repayment){
repayment.addEventListener("submit",(e)=>{
  e.preventDefault()
  $.ajax({
    url:"repayment",
    method:"post",
    dataType:"json",
    data:$("#repayment").serialize(),
    success:(response)=>{
      if(response.status=='complete'){
        alert("Successfully repayment done")
        location.href='index'
      }
      else if(response.status==-2){
        location.href="request_login"
      }
      else{
        alert("No Loan")
      }
    }
  })
})
}
