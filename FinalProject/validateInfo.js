function validateInfo() {
    if (checkName() == false|| checkEmail() == false|| checkPostalcode() == false)  {
		alert ("Please fill up the field properly");
		return false;
	}  else {
		return true;
	}
	
}
		
function checkName(){
    const errorEmail = document.getElementById('error-myName');
	var name = document.getElementById("myName").value;

		if (name.trim() === "") {
			/*	alert("Please fill up your name."); */
				errorName.textContent = 'Please fill up your name.';
				return false;
			} else if (!/^[a-zA-Z\s]+$/.test(name)) {
             /*   alert("Name must contain only alphabet characters and spaces."); */
				errorName.textContent =  "Name must contain only alphabet characters and spaces.";
                return false;
            } else { 
				errorName.textContent = '';
				return true;
			}
		}

function checkEmail(){
    const errorEmail = document.getElementById('error-email');
	var email = document.getElementById("myEmail").value;

	if (email.trim() === "") {
			/*	alert("Please fill up your email."); */
			errorEmail.textContent = "Please fill up your email.";
				return false;
			} else if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
            /* alert("Invalid email address."); */
			errorEmail.textContent = "Invalid Email address";
                return false;
            } else {
			errorEmail.textContent = '';
				return true;
			}
}
			
function checkPostalcode(){
    const errorpostalcode = document.getElementById('error-mypostalcode');
    var postalcode = document.getElementById("mypostalcode").value;
    if (postalcode.trim() === "") {
        /*	alert("Please fill up your email."); */
        errorpostalcode.textContent = "Please fill up your Postal Code.";
            return false;
        } else if (!/^[0-9]+$/.test(email)) {
        /* alert("Invalid email address."); */
        errorpostalcode.textContent = "Invalid Delivery Postal Code";
            return false;
        } else {
        errorpostalcode.textContent = '';
            return true;
        }
}