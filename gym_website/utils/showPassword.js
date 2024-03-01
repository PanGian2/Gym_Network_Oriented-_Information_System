function showPassword(targetID) {
    const x = document.getElementById(targetID)

    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}