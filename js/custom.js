document.addEventListener('DOMContentLoaded', function () {
  const navs = document.querySelectorAll('[data-scrollto]')
  navs.forEach(n => {
    n.addEventListener('click', function (e) {
      const target = document.getElementById(e.target.closest('[data-scrollto]').dataset.scrollto)
      if (target) {
        e.preventDefault()
        window.scrollTo({
          top: target.offsetTop,
          behavior: "smooth"
        });
      }
    })
  })

  const forms = document.querySelectorAll('.form')
  forms.forEach(f => {
    f.onsubmit = (e) => {
      e.preventDefault()
      const data = new FormData(e.target)
      data.append('action', 'ip_send_email');
      data.append('nonce', myajax.nonce);
      data.append('is_user_logged_in', myajax.is_user_logged_in);
      request = {
        method: "POST",
        credentials: 'same-origin',
        body: data
      }
      fetch(myajax.url, request)
        .then((response) => response.json())
        .then((res) => {
          if (res) {
            if (res[0] == 1) {
              alert(__('Your message has been sent. Thank you!'))
              if (f.closest(".modal")) {
                $(f.closest(".modal")).find('.btn-close').trigger("click")
              }
            } else {
              alert(__('Error sending message! Try again.'))
            }
          }
        })
    }
  })


  $("body").on("click", ".ajax-btn", (e) => {
    const formID = e.target.dataset.target
    if (formID) {
      $(formID).find("[type=submit]").trigger("click")
    }
  })

  // Всплывающая форма связи
  var modal = document.getElementById('modalCallback')
  modal.addEventListener('show.bs.modal', function (e) {
    var button = e.relatedTarget
    const formTitle = button.getAttribute("data-bs-title")
    const subjectInput = modal.querySelector('#trigger')
    const preMessage = button.getAttribute('data-bs-premessage')
    if (formTitle) {
      modal.querySelector('.modal-title').innerHTML = formTitle
      subjectInput.value = formTitle
    }
    if (preMessage) {
      modal.querySelector('#message-text').value = preMessage
    }
  })

  // Переключение локализации
  const list = document.querySelectorAll(".local-changer__item:not([current='1'])")
  list.forEach(l => {
    l.onclick = () => {
      const local = l.dataset.local
      document.cookie = "ip_lang=" + local + "; path=/; expires=" + new Date().getTime() + (2 * 86400) + "; domen=" + window.location.origin
      window.location.reload()
    }
  })

})

// Localization
function __(w) {
  if (localize in window)
    return w in localize ? localize[w] : w;
  else {
    return "[get text]";
  }
}

// Получение куки
// возвращает куки с указанным name, или undefined, если ничего не найдено
function getCookie(name) {
  let matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}