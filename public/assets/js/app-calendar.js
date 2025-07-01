/**
 * App Calendar
 */

/**
 * ! If both start and end dates are same Full calendar will nullify the end date value.
 * ! Full calendar will end the event on a day before at 12:00:00AM thus, event won't extend to the end date.
 * ! We are getting events from a separate file named app-calendar-events.js. You can add or remove events from there.
 *
 **/

'use strict';

let direction = 'ltr';

if (isRtl) {
  direction = 'rtl';
}

document.addEventListener('DOMContentLoaded', function () {
  (function () {
    const calendarEl = document.getElementById('calendar'),
      appCalendarSidebar = document.querySelector('.app-calendar-sidebar'),
      addEventSidebar = document.getElementById('addEventSidebar'),
      appOverlay = document.querySelector('.app-overlay'),
      calendarsColor = {
        Inquiry: 'danger',
        CRQ: 'success',
        SRM: 'info',
        Plan: 'warning'
      },
      offcanvasTitle = document.querySelector('.offcanvas-title'),
      btnToggleSidebar = document.querySelector('.btn-toggle-sidebar'),
      btnSubmit = document.querySelector('button[type="submit"]'),
      btnDeleteEvent = document.querySelector('.btn-delete-event'),
      btnCancel = document.querySelector('.btn-cancel'),
      eventTitle = document.querySelector('#plan'),
      eventStartDate = document.querySelector('#eventStartDate'),
      eventEndDate = document.querySelector('#eventEndDate'),
      eventUrl = document.querySelector('#eventURL'),
      eventLabel = $('#eventLabel'), // ! Using jquery vars due to select2 jQuery dependency
      eventGuests = $('#eventGuests'), // ! Using jquery vars due to select2 jQuery dependency
      eventLocation = document.querySelector('#eventLocation'),
      eventDescription = document.querySelector('#task'),
      allDaySwitch = document.querySelector('.allDay-switch'),
      selectAll = document.querySelector('.select-all'),
      filterInput = [].slice.call(document.querySelectorAll('.input-filter')),
      inlineCalendar = document.querySelector('.inline-calendar');

    let eventToUpdate,
      currentEvents = [], // Assign app-calendar-events.js file events (assume events from API) to currentEvents (browser store/object) to manage and update calender events
      childEvents = [],
      isFormValid = false,
      inlineCalInstance,
      zx = null,
      currentPopover = null;

      // Init event Offcanvas
    const bsAddEventSidebar = new bootstrap.Offcanvas(addEventSidebar);

    var filters = ['crq', 'srm', 'inquiry', 'plan'];

    //! TODO: Update Event label and guest code to JS once select removes jQuery dependency
    // Event Label (select2)
    if (eventLabel.length) {
      function renderBadges(option) {
        if (!option.id) {
          return option.text;
        }
        var $badge =
          "<span class='badge badge-dot bg-" + $(option.element).data('label') + " me-2'> " + '</span>' + option.text;

        return $badge;
      }
      eventLabel.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select value',
        dropdownParent: eventLabel.parent(),
        templateResult: renderBadges,
        templateSelection: renderBadges,
        minimumResultsForSearch: -1,
        escapeMarkup: function (es) {
          return es;
        }
      });
    }

    // Event Guests (select2)
    if (eventGuests.length) {
      function renderGuestAvatar(option) {
        if (!option.id) {
          return option.text;
        }
        var $avatar =
          '</div>' +
          option.text +
          '</div>';

        return $avatar;
      }
      eventGuests.wrap('<div class="position-relative"></div>').select2({
          ajax: {
            url: '/backadmin/s2opt/pic',
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
              return { results: data };
          },
        },
        minimumInputLength: 1,
        placeholder: 'Select PIC',
        dropdownParent: eventGuests.parent(),
        templateResult: function (data) {
            return data.loading ? 'Mencari...' : data.name
        },
        templateSelection: function (data) {
            return data.text || data.name;
        }
      });
    }

    // Event start (flatpicker)
    if (eventStartDate) {
      var start = eventStartDate.flatpickr({
        altFormat: 'Y-m-d',
        onReady: function (selectedDates, dateStr, instance) {
          if (instance.isMobile) {
            instance.mobileInput.setAttribute('step', null);
          }
        }
      });
    }

    // Event end (flatpicker)
    if (eventEndDate) {
      var end = eventEndDate.flatpickr({
        altFormat: 'Y-m-d',
        onReady: function (selectedDates, dateStr, instance) {
          if (instance.isMobile) {
            instance.mobileInput.setAttribute('step', null);
          }
        }
      });
    }

    // Inline sidebar calendar (flatpicker)
    if (inlineCalendar) {
      inlineCalInstance = inlineCalendar.flatpickr({
        monthSelectorType: 'static',
        inline: true
      });
    }

    function offCanvas(data) {
      console.log(data);
    }
    // Event click function
    function eventClick(info) {
      eventToUpdate = info.event;

      if (eventToUpdate.url) {
        info.jsEvent.preventDefault();
        window.open(eventToUpdate.url, '_blank');
        return ;
      }

      let content = eventToUpdate.extendedProps.description || "No additional details";
      childEvents = eventToUpdate.extendedProps.childEvents;
      if (eventToUpdate.extendedProps.childEvents) {
          let childEventList = '<ul>';
          eventToUpdate.extendedProps.childEvents.forEach((child, x) => {
            if(child.route) {
              childEventList += `<li><a href="${child.route}" target="_blank">${child.title}</a></li>`;
            } else {
              childEventList += `<li><a href="javascript:void(0);" class="offData"  data-index="${x}">${child.title}</a></li>`;
            }
          });
          childEventList += '</ul>';
          content += childEventList;
      } else {
          content += 'No data';
      }

      let popoverContent = `
      <div style="position: relative; padding: 10px;">
        <div>${content}</div>
        <div style="text-align: right; margin-top: 10px;">
          <button type="button" class="btn btn-secondary btn-sm btn-close-popover w-100">Close</button>
        </div>
      </div>`;

      // Close any existing popover
      if (currentPopover) {
        currentPopover.dispose();
        currentPopover = null;
      }

      // Create a new popover
      currentPopover = new bootstrap.Popover(info.el, {
        title: eventToUpdate.title,
        content: popoverContent,
        html: true,
        placement: 'top',
        trigger: 'manual',
        sanitize  : false
      });

      // Show the popover
      currentPopover.show();
      // Attach event listener to close button after popover renders
      setTimeout(() => {
        const closeButton = document.querySelector('.popover .btn-close-popover');
        if (closeButton) {
          closeButton.addEventListener('click', () => {
            currentPopover.dispose();
            currentPopover = null;
          });
        }
      }, 10);
    }

    // Modify sidebar toggler
    function modifyToggler() {
      const fcSidebarToggleButton = document.querySelector('.fc-sidebarToggle-button');
      fcSidebarToggleButton.classList.remove('fc-button-primary');
      fcSidebarToggleButton.classList.add('d-lg-none', 'd-inline-block', 'ps-0');
      while (fcSidebarToggleButton.firstChild) {
        fcSidebarToggleButton.firstChild.remove();
      }
      fcSidebarToggleButton.setAttribute('data-bs-toggle', 'sidebar');
      fcSidebarToggleButton.setAttribute('data-overlay', '');
      fcSidebarToggleButton.setAttribute('data-target', '#app-calendar-sidebar');
      fcSidebarToggleButton.insertAdjacentHTML('beforeend', '<i class="ti ti-menu-2 ti-sm"></i>');
    }

    // Filter events by calender
    function selectedCalendars() {
      let selected = [],
        filterInputChecked = [].slice.call(document.querySelectorAll('.input-filter:checked'));

      filterInputChecked.forEach(item => {
        selected.push(item.getAttribute('data-value'));
      });
      return selected;
    }

    // Init FullCalendar
    // ------------------------------------------------
    let calendar = new Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      events: {
        url: '/backadmin/calendar/data',
        extraParams: function(){
          return {
            filters: filters
          }
        },
        failure: function() {
            alert('there was an error while fetching events!');
        },
      },
      plugins: [dayGridPlugin, interactionPlugin, listPlugin, timegridPlugin],
      editable: true,
      dragScroll: true,
      dayMaxEvents: 2,
      eventResizableFromStart: true,
      customButtons: {
        sidebarToggle: {
          text: 'Sidebar'
        }
      },
      headerToolbar: {
        start: 'sidebarToggle, prev,next, title',
        end: 'dayGridMonth,listMonth'
      },
      displayEventTime:false,
      direction: direction,
      initialDate: new Date(),
      navLinks: true, // can click day/week names to navigate views
      eventClassNames: function ({ event: calendarEvent }) {
        const colorName = calendarsColor[calendarEvent._def.extendedProps.calendar];
        // Background Color
        return ['fc-event-' + colorName];
      },
      dateClick: function (info) {
      
      },
      eventClick: function (info) {
        eventClick(info)
      },
      datesSet: function () {
        modifyToggler();
      },
      viewDidMount: function () {
        modifyToggler();
      }
    });

    // Render calendar
    calendar.render();
    // Modify sidebar toggler
    modifyToggler();

    const eventForm = document.getElementById('eventForm');
    const fv = FormValidation.formValidation(eventForm, {
      fields: {
        eventTitle: {
          validators: {
            notEmpty: {
              message: 'Please enter event title '
            }
          }
        },
        eventStartDate: {
          validators: {
            notEmpty: {
              message: 'Please enter start date '
            }
          }
        },
        eventEndDate: {
          validators: {
            notEmpty: {
              message: 'Please enter end date '
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          eleValidClass: '',
          rowSelector: function (field, ele) {
            // field is the field name & ele is the field element
            return '.mb-3';
          }
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        // Submit the form when all fields are valid
        // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    })
      .on('core.form.valid', function () {
        // Jump to the next step when all fields in the current step are valid
        isFormValid = true;
      })
      .on('core.form.invalid', function () {
        // if fields are invalid
        isFormValid = false;
      });

    // Add Event
    // ------------------------------------------------
    function addEvent(eventData) {
      // ? Add new event data to current events object and refetch it to display on calender
      // ? You can write below code to AJAX call success response

      $.ajax({
          type: "post",
          url: "/backadmin/ticket/crq-plan",
          data: {
              data: eventData,
              _token: _token
          },
          success: function (res) {
            calendar.refetchEvents();
            toastr.success(res.message, "CRQ Plan")
          },
          error: function(res) {
            toastr.error("Something went wrong!", "Oops!");
          }
      });
      // currentEvents.push(eventData);

      // ? To add event directly to calender (won't update currentEvents object)
      // calendar.addEvent(eventData);
    }

    // Update Event
    // ------------------------------------------------
    function updateEvent(eventData) {
      // ? Update existing event data to current events object and refetch it to display on calender
      // ? You can write below code to AJAX call success response
      $.ajax({
          type: "post",
          url: `/backadmin/ticket/crq-plan/${eventData.extends.id}`,
          data: {
              data: eventData,
              _token: _token
          },
          success: function (res) {
            calendar.refetchEvents();
            toastr.success(res.message, "CRQ Plan")
          },
          error: function(res) {
            toastr.error("Something went wrong!", "Oops!");
          }
      });

      // ? To update event directly to calender (won't update currentEvents object)
      // let propsToUpdate = ['id', 'title', 'url'];
      // let extendedPropsToUpdate = ['calendar', 'guests', 'location', 'description'];

      // updateEventInCalendar(eventData, propsToUpdate, extendedPropsToUpdate);
    }

    // Remove Event
    // ------------------------------------------------

    function removeEvent(eventId) {
      // ? Delete existing event data to current events object and refetch it to display on calender
      // ? You can write below code to AJAX call success response
      

      $.ajax({
        type: "post",
        url: `/backadmin/ticket/crq-plan-delete/${eventId}`,
        data: {
            _token: _token
        },
        success: function (res) {
          calendar.refetchEvents();
          toastr.success(res.message, "CRQ Plan")
        },
        error: function(res) {
          toastr.error("Something went wrong!", "Oops!");
        }
      });
    
      // ? To delete event directly to calender (won't update currentEvents object)
      // removeEventInCalendar(eventId);
    }

    // (Update Event In Calendar (UI Only)
    // ------------------------------------------------
    const updateEventInCalendar = (updatedEventData, propsToUpdate, extendedPropsToUpdate) => {
      const existingEvent = calendar.getEventById(updatedEventData.id);

      // --- Set event properties except date related ----- //
      // ? Docs: https://fullcalendar.io/docs/Event-setProp
      // dateRelatedProps => ['start', 'end', 'allDay']
      // eslint-disable-next-line no-plusplus
      for (var index = 0; index < propsToUpdate.length; index++) {
        var propName = propsToUpdate[index];
        existingEvent.setProp(propName, updatedEventData[propName]);
      }

      // --- Set date related props ----- //
      // ? Docs: https://fullcalendar.io/docs/Event-setDates
      existingEvent.setDates(updatedEventData.start, updatedEventData.end, {
        allDay: updatedEventData.allDay
      });

      // --- Set event's extendedProps ----- //
      // ? Docs: https://fullcalendar.io/docs/Event-setExtendedProp
      // eslint-disable-next-line no-plusplus
      for (var index = 0; index < extendedPropsToUpdate.length; index++) {
        var propName = extendedPropsToUpdate[index];
        existingEvent.setExtendedProp(propName, updatedEventData.extendedProps[propName]);
      }
    };

    // Remove Event In Calendar (UI Only)
    // ------------------------------------------------
    function removeEventInCalendar(eventId) {
      calendar.getEventById(eventId).remove();
    }

    document.addEventListener('click', function(event) {
      if (
        currentPopover &&
        !calendarEl.contains(event.target) &&
        !event.target.closest('.popover')
      ) {
        currentPopover.dispose();
        currentPopover = null;
      }
    });

    $(document).on('click', '.offData', function(e) {
      var x = $(this).data('index');
      zx = $(this).data('index');
      currentPopover.dispose();
      currentPopover = null;
      bsAddEventSidebar.show();
      // For update event set offcanvas title text: Update Event
      if (offcanvasTitle) {
        offcanvasTitle.innerHTML = 'Update Plan';
      }
      btnSubmit.innerHTML = 'Update';
      btnSubmit.classList.add('btn-update-event');
      btnSubmit.classList.remove('btn-add-event');
      btnDeleteEvent.classList.remove('d-none');
      eventTitle.value = childEvents[x].plan;
      start.setDate(childEvents[x].start_date, true, 'Y-m-d');
      childEvents[x].end_date !== null
        ? end.setDate(childEvents[x].end_date, true, 'Y-m-d')
        : end.setDate(childEvents[x].start_date, true, 'Y-m-d');
      childEvents[x].task !== undefined
        ? (eventDescription.value = childEvents[x].task)
        : null;

      if(childEvents[x].pic?.name) {
        let option = new Option(childEvents[x].pic.name, childEvents[x].pic.id, true, true);
        $('.select-event-guests').append(option).trigger('change');
      }
    });
    // Add new event
    // ------------------------------------------------
    btnSubmit.addEventListener('click', e => {
      if (btnSubmit.classList.contains('btn-add-event')) {
        if (isFormValid) {
          let newEvent = {
            plan: eventTitle.value,
            start_date: eventStartDate.value,
            end_date: eventEndDate.value,
            pic_id: eventGuests.val(),
            task: eventDescription.value
          };
          addEvent(newEvent);
          bsAddEventSidebar.hide();
        }
      } else {
        // Update event
        // ------------------------------------------------
        if (isFormValid) {
          let eventData = {
            id: eventToUpdate.id,
            plan: eventTitle.value,
            start_date: eventStartDate.value,
            end_date: eventEndDate.value,
            pic_id: eventGuests.val(),
            task: eventDescription.value,
            extends: {
              id : eventToUpdate.extendedProps.childEvents[zx].id
            }
          };

          updateEvent(eventData);
          bsAddEventSidebar.hide();
        }
      }
    });

    // Call removeEvent function
    btnDeleteEvent.addEventListener('click', e => {
      removeEvent(eventToUpdate.extendedProps.childEvents[zx].id);
      // eventToUpdate.remove();
      bsAddEventSidebar.hide();
    });

    // Reset event form inputs values
    // ------------------------------------------------
    function resetValues() {
      eventEndDate.value = '';
      eventStartDate.value = '';
      eventTitle.value = '';
      eventGuests.val('').trigger('change');
      eventDescription.value = '';
    }
    // When modal hides reset input values
    addEventSidebar.addEventListener('hidden.bs.offcanvas', function () {
      resetValues();
    });

    // Hide left sidebar if the right sidebar is open
    btnToggleSidebar.addEventListener('click', e => {
      if (offcanvasTitle) {
        offcanvasTitle.innerHTML = 'Add Event';
      }
      btnSubmit.innerHTML = 'Add';
      btnSubmit.classList.remove('btn-update-event');
      btnSubmit.classList.add('btn-add-event');
      btnDeleteEvent.classList.add('d-none');
      appCalendarSidebar.classList.remove('show');
      appOverlay.classList.remove('show');
    });

    // Calender filter functionality
    // ------------------------------------------------
    if (selectAll) {
      selectAll.addEventListener('click', e => {
        if (e.currentTarget.checked) {
          filters = ['srm', 'crq', 'inquiry', 'plan'];
          document.querySelectorAll('.input-filter').forEach(c => (c.checked = 1));
        } else {
          filters = [];
          document.querySelectorAll('.input-filter').forEach(c => (c.checked = 0));
        }
        calendar.refetchEvents();
      });
    }

    if (filterInput) {
      filterInput.forEach(item => {
        item.addEventListener('click', () => {
          let val = event.target.getAttribute('data-value');
          if(event.target.checked) filters.push(val);
          else filters = filters.filter(x => x != val);
          document.querySelectorAll('.input-filter:checked').length < document.querySelectorAll('.input-filter').length
            ? (selectAll.checked = false)
            : (selectAll.checked = true);
          calendar.refetchEvents();
        });
      });
    }

    // Jump to date on sidebar(inline) calendar change
    inlineCalInstance.config.onChange.push(function (date) {
      calendar.changeView(calendar.view.type, moment(date[0]).format('YYYY-MM-DD'));
      modifyToggler();
      appCalendarSidebar.classList.remove('show');
      appOverlay.classList.remove('show');
    });
  })();
});
