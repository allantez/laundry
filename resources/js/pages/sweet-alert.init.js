/**
 * Theme: Approx - Bootstrap 5 Responsive Admin Dashboard
 * Author: Mannatthemes
 * Sweet Alert Js
 */
import Swal from "sweetalert2";

if (document.getElementById("TriggerModalToast"))
  document.getElementById("TriggerModalToast").addEventListener("click", function () {
    Swal.bindClickHandler()
    Swal.mixin({
      toast: true,
    }).bindClickHandler('data-swal-toast-template')
  });

if (document.getElementById("success"))
  document.getElementById("success").addEventListener("click", function () {
    Swal.fire({
      icon: 'success',
      title: 'Your work has been saved',
      timer: 1500
    })
  });

if (document.getElementById("success"))
  document.getElementById("success").addEventListener("click", function () {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Something went wrong!',
    })
  });


if (document.getElementById("error"))
  document.getElementById("error").addEventListener("click", function () {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Something went wrong!',
    })
  });

if (document.getElementById("warning"))
  document.getElementById("warning").addEventListener("click", function () {
    Swal.fire({
      icon: 'warning',
      title: 'Oops...',
      text: 'Icon warning!',
    })
  });

if (document.getElementById("info"))
  document.getElementById("info").addEventListener("click", function () {
    Swal.fire({
      icon: 'info',
      title: 'Oops...',
      text: 'Icon Info!',
    })
  });

if (document.getElementById("question"))
  document.getElementById("question").addEventListener("click", function () {
    Swal.fire({
      icon: 'question',
      title: 'Oops...',
      text: 'Icon question!',
    })
  });

if (document.getElementById("basicMessage"))
  document.getElementById("basicMessage").addEventListener("click", function () {
    Swal.fire('Any fool can use a computer')

  });

if (document.getElementById("titleText"))
  document.getElementById("titleText").addEventListener("click", function () {
    Swal.fire(
      'The Internet?',
      'That thing is still around?',
      'question'
    )
  });

if (document.getElementById("errorType"))
  document.getElementById("errorType").addEventListener("click", function () {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Something went wrong!',
      footer: '<a href>Why do I have this issue?</a>'
    })
  });

if (document.getElementById("customHtml"))
  document.getElementById("customHtml").addEventListener("click", function () {
    Swal.fire({
      title: '<strong>HTML <u>example</u></strong>',
      icon: 'info',
      html:
        'You can use <b>bold text</b>, ' +
        '<a href="//sweetalert2.github.io">links</a> ' +
        'and other HTML tags',
      showCloseButton: true,
      showCancelButton: true,
      focusConfirm: false,
      confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Great!',
      confirmButtonAriaLabel: 'Thumbs up, great!',
      cancelButtonText:
        '<i class="fa fa-thumbs-down"></i>',
      cancelButtonAriaLabel: 'Thumbs down'
    })
  });

if (document.getElementById("threeButtons"))
  document.getElementById("threeButtons").addEventListener("click", function () {
    Swal.fire({
      title: 'Do you want to save the changes?',
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: `Save`,
      denyButtonText: `Don't save`,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        Swal.fire('Saved!', '', 'success')
      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info')
      }
    })
  });

if (document.getElementById("customPosition"))
  document.getElementById("customPosition").addEventListener("click", function () {
    Swal.fire({
      position: 'top-end',
      icon: 'success',
      title: 'Your work has been saved',
      showConfirmButton: false,
      timer: 1500
    })
  });

if (document.getElementById("customAnimation"))
  document.getElementById("customAnimation").addEventListener("click", function () {
    Swal.fire({
      position: 'top-end',
      icon: 'success',
      title: 'Your work has been saved',
      showConfirmButton: false,
      timer: 1500
    })
  });

if (document.getElementById("warningConfirm"))
  document.getElementById("warningConfirm").addEventListener("click", function () {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
    }).then(function (result) {
      if (result.isConfirmed) {
        Swal.fire(
          'Deleted!',
          'Your file has been deleted.',
          'success'
        )
      }
    })
  });

if (document.getElementById("handleDismiss"))
  document.getElementById("handleDismiss").addEventListener("click", function () {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger me-2'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        swalWithBootstrapButtons.fire(
          'Deleted!',
          'Your file has been deleted.',
          'success'
        )
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'Your imaginary file is safe :)',
          'error'
        )
      }
    })
  });

if (document.getElementById("customImage"))
  document.getElementById("customImage").addEventListener("click", function () {
    Swal.fire({
      title: 'Approx!',
      text: 'Modal with a Brand Logo.',
      imageUrl: '/images/logo-sm.png',
      imageWidth: 80,
      imageHeight: 80,
      imageAlt: 'Custom image',
    })
  });

if (document.getElementById("customWidth"))
  document.getElementById("customWidth").addEventListener("click", function () {
    Swal.fire({
      title: 'Custom width, padding, background.',
      width: 600,
      padding: 50,
      background: 'rgba(254,254,254,0.01)  url(/images/bg-body.jpg) ',
      backgroundSize: 'cover',
      backgroundPosition: 'center',
    })
  });

if (document.getElementById("timer"))
  document.getElementById("timer").addEventListener("click", function () {
    let timerInterval
    Swal.fire({
      title: 'Auto close alert!',
      html: 'I will close in <b></b> milliseconds.',
      timer: 2000,
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading()
        timerInterval = setInterval(() => {
          const content = Swal.getContent()
          if (content) {
            const b = content.querySelector('b')
            if (b) {
              b.textContent = Swal.getTimerLeft()
            }
          }
        }, 100)
      },
      willClose: () => {
        clearInterval(timerInterval)
      }
    }).then((result) => {
      /* Read more about handling dismissals below */
      if (result.dismiss === Swal.DismissReason.timer) {
        console.log('I was closed by the timer')
      }
    })
  });

if (document.getElementById("rtl"))
  document.getElementById("rtl").addEventListener("click", function () {
    Swal.fire({
      title: 'هل تريد الاستمرار؟',
      icon: 'question',
      iconHtml: '؟',
      confirmButtonText: 'نعم',
      cancelButtonText: 'لا',
      showCancelButton: true,
      showCloseButton: true
    })
  });

if (document.getElementById("ajaxRequest"))
  document.getElementById("ajaxRequest").addEventListener("click", function () {
    Swal.fire({
      title: 'Submit your Github username',
      input: 'text',
      inputAttributes: {
        autocapitalize: 'off'
      },
      showCancelButton: true,
      confirmButtonText: 'Look up',
      showLoaderOnConfirm: true,
      preConfirm: (login) => {
        return fetch(`//api.github.com/users/${login}`)
          .then(response => {
            if (!response.ok) {
              throw new Error(response.statusText)
            }
            return response.json()
          })
          .catch(error => {
            Swal.showValidationMessage(
              `Request failed: ${error}`
            )
          })
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: `${result.value.login}'s avatar`,
          imageUrl: result.value.avatar_url
        })
      }
    })
  });

if (document.getElementById("chainingModals"))
  document.getElementById("chainingModals").addEventListener("click", function () {
    Swal.mixin({
      input: 'text',
      confirmButtonText: 'Next &rarr;',
      showCancelButton: true,
      progressSteps: ['1', '2', '3']
    }).queue([
      {
        title: 'Question 1',
        text: 'Chaining swal2 modals is easy'
      },
      'Question 2',
      'Question 3'
    ]).then((result) => {
      if (result.value) {
        const answers = JSON.stringify(result.value)
        Swal.fire({
          title: 'All done!',
          html: `
                Your answers:
                <pre><code>${answers}</code></pre>
              `,
          confirmButtonText: 'Lovely!'
        })
      }
    })
  });

if (document.getElementById("dynamicQueue"))
  document.getElementById("dynamicQueue").addEventListener("click", function () {
    const ipAPI = '//api.ipify.org?format=json'

    Swal.queue([{
      title: 'Your public IP',
      confirmButtonText: 'Show my public IP',
      text:
        'Your public IP will be received ' +
        'via AJAX request',
      showLoaderOnConfirm: true,
      preConfirm: () => {
        return fetch(ipAPI)
          .then(response => response.json())
          .then(data => Swal.insertQueueStep(data.ip))
          .catch(() => {
            Swal.insertQueueStep({
              icon: 'error',
              title: 'Unable to get your public IP'
            })
          })
      }
    }])
  });

if (document.getElementById("mixin"))
  document.getElementById("mixin").addEventListener("click", function () {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })

    Toast.fire({
      icon: 'success',
      title: 'Signed in successfully'
    })
  });

if (document.getElementById("declarativeTemplate"))
  document.getElementById("declarativeTemplate").addEventListener("click", function () {
    Swal.fire({
      template: '#my-template',
    })
  });

if (document.getElementById("declarativeTemplate"))
  document.getElementById("declarativeTemplate").addEventListener("click", function () {
    Swal.bindClickHandler()
    Swal.mixin({
      toast: true,
    }).bindClickHandler('data-swal-toast-template')
    return;
  });

