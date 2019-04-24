var CiuisCRM = angular.module('Ciuis', ['Ciuis.datepicker', 'ngMaterial', 'ngMaterialDatePicker', 'currencyFormat', 'ciuisscheduler'])
	.config(function ($mdGestureProvider) {
		"use strict";
		$mdGestureProvider.skipClickHijack();
	});

var globals = {};

function Ciuis_Controller($scope, $http, $mdSidenav, $filter, $interval, $mdDialog, $element) {
	"use strict";

	$scope.lang;

	$scope.date = new Date();
	$scope.tick = function () { 
		$scope.clock = Date.now();
	};
	$scope.tick();
	$interval($scope.tick, 1000);
	var curDate = new Date();
	var y = curDate.getFullYear();
	var m = curDate.getMonth() + 1; 
	if (m < 10) {
		m = '0' + m;
	}
	var d = curDate.getDate();
	if (d < 10) {
		d = '0' + d;
	}
	$scope.curDate = y + '-' + m + '-' + d;
	$scope.appurl = BASE_URL;
	$scope.UPIMGURL = UPIMGURL;
	//$scope.IMAGESURL = BASE_URL + 'assets/img/';
	//$scope.SETFILEURL = BASE_URL + 'uploads/ciuis_settings/';
	$scope.ONLYADMIN = SHOW_ONLY_ADMIN;
	$scope.USERNAMEIN = LOGGEDINSTAFFNAME;
	$scope.USERAVATAR = LOGGEDINSTAFFAVATAR;
	$scope.activestaff = ACTIVESTAFF;
	$scope.cur_symbol = CURRENCY;
	$scope.cur_code = CURRENCY; 
	$scope.cur_lct = LOCATE_SELECTED;

	$http.get(BASE_URL + 'api/settings').then(function (Settings) {
		$scope.settings = Settings.data;
		var setapp = $scope.settings;
		$scope.applogo = (setapp.logo);
		$scope.getTimer();
	});

	$http.get(BASE_URL + 'api/leftmenu').then(function (LeftMenu) {
		$scope.all_menu_item = LeftMenu.data;
		$scope.menu = $filter('filter')($scope.all_menu_item, {
			show: 'true',
			show_staff: '0',
		});
	});

	$http.get(BASE_URL + 'api/stats').then(function (Stats) {
		$scope.stats = Stats.data;
	});

	$http.get(BASE_URL + 'api/menu').then(function (Navbar) {
		$scope.navbar = Navbar.data;
	});

	$http.get(BASE_URL + 'api/user').then(function (Userman) {
		$scope.user = Userman.data;

		if ($scope.user.appointment_availability === '1') {
			$scope.appointment_availability = true;
		} else {
			$scope.appointment_availability = false;
		}

		$http.get(BASE_URL + 'api/lang/' + $scope.user.language).then(function (Language) {
			$scope.lang = Language.data;
		});

		$scope.ChangeAppointmentAvailability = function (id, value) {
			$http.post(BASE_URL + 'staff/appointment_availability/' + id + '/' + value)
				.then(
					function (response) {
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$scope.ChangeLanguage = function (lang) {
		$http.get(BASE_URL + 'api/lang/' + lang).then(function (Language) {
			$scope.lang = Language.data;
		});
	};

	$http.get(BASE_URL + 'api/transactions').then(function (Transactions) {
		$scope.transactions = Transactions.data;
	});

	$http.get(BASE_URL + 'api/dueinvoices').then(function (Dueinvoices) {
		$scope.dueinvoices = Dueinvoices.data;
	});

	$http.get(BASE_URL + 'api/overdueinvoices').then(function (Overdueinvoices) {
		$scope.overdueinvoices = Overdueinvoices.data;
	});

	$http.get(BASE_URL + 'api/newtickets').then(function (Newtickets) {
		$scope.newtickets = Newtickets.data;
	});

	$http.get(BASE_URL + 'api/notifications').then(function (Notifications) {
		$scope.notifications = Notifications.data;
	});

	$http.get(BASE_URL + 'api/logs').then(function (Logs) {
		$scope.logs = Logs.data;
	});

	$http.get(BASE_URL + 'api/projects').then(function (Projects) {
		$scope.projects = Projects.data;
	});

	$http.get(BASE_URL + 'api/staff').then(function (Staff) {
		$scope.staff = Staff.data;
	});

	$http.get(BASE_URL + 'api/events').then(function (Events) {
		$scope.events = Events.data;
	});

	$http.get(BASE_URL + 'api/appointments').then(function (appointments) {
		$scope.dashboard_appointments = appointments.data;
	});

	$http.get(BASE_URL + 'api/meetings').then(function (Meetings) {
		$scope.meetings = Meetings.data;
	});

	$http.get(BASE_URL + 'api/todos').then(function (Todos) {
		$scope.todos = Todos.data;
	});

	$http.get(BASE_URL + 'api/donetodos').then(function (Donetodos) {
		$scope.tododone = Donetodos.data;
	});

	$http.get(BASE_URL + 'api/reminders').then(function (Reminders) {
		$scope.reminders = Reminders.data;
	});

	$http.get(BASE_URL + 'api/countries').then(function (Countries) {
		$scope.countries = Countries.data; 
	});

	$http.get(BASE_URL + 'api/customers').then(function (Customers) {
		$scope.all_customers = Customers.data;
	});

	$http.get(BASE_URL + 'api/contacts').then(function (Contacts) {
		$scope.all_contacts = Contacts.data;
	});

	$http.get(BASE_URL + 'api/departments').then(function (Departments) {
		$scope.departments = Departments.data;
	});

	$scope.OpenMenu = function () {
		$('#mobile-menu').show();
	};

	$scope.EventForm = buildToggler('EventForm');
	$scope.SetOnsiteVisit = buildToggler('SetOnsiteVisit');
	$scope.Notifications = buildToggler('Notifications');
	$scope.Todo = buildToggler('Todo');
	$scope.Profile = buildToggler('Profile');
	$scope.PickUpTo = buildToggler('PickUpTo');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();
		};
	}

	$scope.searchNav = function() {
		$mdSidenav('searchNav').toggle();
		$scope.searchInputMsg = 1;
	}

	$scope.close = function () {
		$mdSidenav('EventForm').close();
		$mdSidenav('SetOnsiteVisit').close();
		$mdSidenav('Notifications').close();
		$mdSidenav('Todo').close();
		$mdSidenav('Profile').close();
		$mdSidenav('PickUpTo').close();
		$mdSidenav('searchNav').close();
		$mdDialog.hide();
		$('#mobile-menu').hide();
	};

	function resetSearch() {
		$scope.searchProposals = [];
		$scope.searchLeads = [];
		$scope.searchInvoices = [];
		$scope.searchStaff = [];
		$scope.searchCustomers = [];
		$scope.searchLeads = [];
		$scope.searchExpenses = [];
		$scope.searchProjects = [];
		$scope.searchProducts = [];
		$scope.searchTickets = [];
		$scope.searchTasks = [];
		$scope.searchOrders = [];
		$scope.searchProposals.length = 0;
		$scope.searchInvoices.length = 0;
		$scope.searchLoader = 0;
		$scope.searchStaff.length = 0;
		$scope.searchCustomers.length = 0;
		$scope.searchLeads.length = 0;
		$scope.searchExpenses.length = 0;
		$scope.searchProjects.length = 0;
		$scope.searchProducts.length = 0;
		$scope.searchTickets.length = 0;
		$scope.searchTasks.length = 0;
		$scope.searchOrders.length = 0;
	}
	$scope.searchInput = function(input) {
		if (input.length > 1) {
			$scope.searchInputMsg = 0;
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var dataObj = $.param({
				input: input
			});
			var posturl = BASE_URL + 'api/search';
			$scope.searchResult = 0;
			$scope.searchLoader = 1;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.searchLoader = 0;
						$scope.searchResult = 0;
						if (response.data.length > 0) {
							$scope.searchResult = 0;
							for (var i = 0; i < response.data.length; i++) {
								if (response.data[i].type == 'proposals') {
									$scope.searchProposals = response.data[i].result;
								}
								if (response.data[i].type == 'invoices') {
									$scope.searchInvoices = response.data[i].result;
								}
								if (response.data[i].type == 'staff') {
									$scope.searchStaff = response.data[i].result;
								}
								if (response.data[i].type == 'customers') {
									$scope.searchCustomers = response.data[i].result;
								}
								if (response.data[i].type == 'leads') {
									$scope.searchLeads = response.data[i].result;
								}
								if (response.data[i].type == 'expenses') {
									$scope.searchExpenses = response.data[i].result;
								}
								if (response.data[i].type == 'projects') {
									$scope.searchProjects = response.data[i].result;
								}
								if (response.data[i].type == 'products') {
									$scope.searchProducts = response.data[i].result;
								}
								if (response.data[i].type == 'tickets') {
									$scope.searchTickets = response.data[i].result;
								}
								if (response.data[i].type == 'tasks') {
									$scope.searchTasks = response.data[i].result;
								}
								if (response.data[i].type == 'orders') {
									$scope.searchOrders = response.data[i].result;
								}
							}
						} else {
							$scope.searchResult = 1;
							resetSearch();
						}
					}, function() {
						$scope.searchResult = 0;
					});
		} else if (input == '') {
			$scope.searchInputMsg = 1;
			$scope.searchResult = 0;
			resetSearch();
		} else {
			$scope.searchResult = 1;
			$scope.searchInputMsg = 0;
			resetSearch();
		}
	}

	$scope.getTimer = function() {
		$scope.timer = {};
		$scope.timer.loading = true;
		$scope.timer.start = false;
		$scope.timer.stop = false;
		$scope.timer.found = false;
		var dataObj = $.param({});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'api/get_timer';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					if (response.data.success == true) {
						$scope.timer.loading = false;
						if (response.data.status == true) {
							$('#timerStarted').addClass('text-success');
							$scope.timer.start = false;
							$scope.timer.stop = true;
							$scope.timer.found = false;
							$scope.timer.started = response.data.result.started;
							$scope.timer.total = response.data.result.total;
							$scope.timer.task_id = response.data.result.task_id;
							$scope.timer.task = response.data.result.task;
							globals.task_id = response.data.result.task_id;
							globals.timer_note = response.data.result.note;
						} else {
							$scope.timer.start = true;
							$scope.timer.stop = false;
							$scope.timer.found = true;
						}
					} else {
						showToast(NTFTITLE, response.data.message, 'danger');
					}
				},
				function (response) {
					console.log(response);
				}
			);
	}

	$scope.startTimer = function(action) {
		if (action == 'stop') {
			$scope.stopTimerWithTask('stop');
		} else  {
			var dataObj = $.param({
				status: action,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'api/timer';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						if (response.data.success == true) {
							showToast(response.data.type, response.data.message, 'success');
							$scope.getTimer();
						}
					},
					function (response) {
						console.log(response);
					}
				);
		}
	}

	$scope.stopTimerWithTask = function(action) {
		$mdDialog.show({
	      	templateUrl: 'timerTasks.html',
	      	parent: angular.element(document.body),
	      	clickOutsideToClose: false,
	      	fullscreen: false,
	      	escapeToClose: false,
	      	controller: NewTeamDialogController,
	    });

	    function NewTeamDialogController($scope, $mdDialog) {  
	    	$scope.taskTimer = {};
			$scope.taskTimer.loader = true;
			if (action == 'assign') {
				$scope.taskTimer.assign = true;
				$scope.taskTimer.stop = false;
			}
			if (action == 'stop') {
				$scope.taskTimer.assign = false;
				$scope.taskTimer.stop = true;
			}
		    $http.get(BASE_URL + 'api/get_open_tasks').then(function (Tasks) {
				$scope.timerTasks = Tasks.data;
				console.log($scope.tasks)
				$scope.taskTimer.loader = false;
			});

			$scope.searchTerm;
			$scope.clearSearchTerm = function() {
				$scope.searchTerm = '';
			};
			$element.find('input').on('keydown', function(ev) {
				ev.stopPropagation();
			});
			//
			$scope.stopTimer = {};
			$scope.stopTimer.task = globals.task_id;
			$scope.stopTimer.note = globals.timer_note;
			$scope.close = function(){$mdDialog.hide();}

			$scope.timerStopConfirm = function() {
				if (!$scope.stopTimer) {
					var dataObj = $.param({
						status: 'stop',
						task: '',
						note: '',
						action: action
					});
				} else {
					var dataObj = $.param({
						status: 'stop',
						task: $scope.stopTimer.task,
						note: $scope.stopTimer.note,
						action: action
					});
				}
					
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				var posturl = BASE_URL + 'api/timer';
				$http.post(posturl, dataObj, config)
					.then(
						function (response) {
							if (response.data.success == true) {
								showToast(response.data.type, response.data.message, 'success');
								$mdDialog.hide();
								if (action == 'stop') {
									console.log(action)
									$('#timerStarted').removeClass('text-success');
									$('#timerStarted').addClass('text-muted');
								}
							} else {
								showToast(NTFTITLE, response.data.message, 'danger');
							}
						},
						function (response) {
							console.log(response);
						}
					);
			}
	    } 
	}

	$scope.viewTimesheet = function() {
		localStorage.clear();
		var findPath = {};
		findPath.type = 'report';
		findPath.view = 'timesheet';
		localStorage.setItem('findPath', JSON.stringify(findPath));
		window.location.href = BASE_URL + 'report';
	}

	$scope.AddEvent = function () {
		var dataObj = $.param({
			title: $scope.event_title,
			public: $scope.event_public,
			detail: $scope.event_detail,
			eventstart: moment($scope.event_start).format("YYYY-MM-DD HH:mm:ss"),
			eventend: moment($scope.event_end).format("YYYY-MM-DD HH:mm:ss"),
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'calendar/addevent';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					console.log(response);
					$mdSidenav('EventForm').close();
					$.gritter.add({
						title: '<b>' + $scope.lang.notification + '</b>',
						text: $scope.lang.eventadded,
						position: 'bottom',
						class_name: 'color success',
					});
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.AddOnsiteVisit = function () {
		var dataObj = $.param({
			title: $scope.onsite_visit.title,
			customer_id: $scope.onsite_visit.customer_id,
			staff_id: $scope.onsite_visit.staff_id,
			description: $scope.onsite_visit.description,
			date: moment($scope.onsite_visit.start).format("YYYY-MM-DD"),
			start: moment($scope.onsite_visit.start).format("HH:mm:ss"),
			end: moment($scope.onsite_visit.end).format("HH:mm:ss"),
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'trivia/set_onsite_visit';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					console.log(response);
					$mdSidenav('SetOnsiteVisit').close();
					$.gritter.add({
						title: '<b>' + response.data + '</b>',
						text: response.data,
						position: 'bottom',
						class_name: 'color success',
					});
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.AddTodo = function () {
		$scope.addingTodo = true
		var dataObj = $.param({
			tododetail: $scope.tododetail,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'trivia/addtodo';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$scope.addingTodo = false
					if (response.data.success) {
						$scope.todos.push({
							'description': $scope.tododetail,
							'date': response.data.messageDate,
						});
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: $scope.lang.todoadded,
							position: 'bottom',
							class_name: 'color success',
						});
						$scope.tododetail = '';
					}
				},
				function (response) {
					$scope.addingTodo = false
					console.log(response);
				}
			);
	};

	$scope.DeleteTodo = function (index) {
		var todo = $scope.todos[index];
		var dataObj = $.param({
			todo: todo.id
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'trivia/removetodo';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$scope.todos.splice($scope.todos.indexOf(todo), 1);
					console.log(response);
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.TodoAsUnDone = function (index) {
		var todo = $scope.tododone[index];
		var dataObj = $.param({
			todo: todo.id
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'trivia/undonetodo', dataObj, config)
			.then(
				function (response) {
					var todo = $scope.tododone[index];
					$scope.tododone.splice($scope.tododone.indexOf(todo), 1);
					$scope.todos.unshift(todo);
					console.log(response);
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.DeleteTodoDone = function (index) {
		var todo = $scope.tododone[index];
		var dataObj = $.param({
			todo: todo.id
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'trivia/removetodo';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$scope.tododone.splice($scope.tododone.indexOf(todo), 1);
					console.log(response);
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.TodoAsDone = function (index) {
		var todo = $scope.todos[index];
		var dataObj = $.param({
			todo: todo.id
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'trivia/donetodo', dataObj, config)
			.then(
				function (response) {
					var todo = $scope.todos[index];
					$scope.todos.splice($scope.todos.indexOf(todo), 1);
					$scope.tododone.unshift(todo);
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: $scope.lang.tododone,
						position: 'bottom',
						class_name: 'color success',
					});
					console.log(response);
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.ReminderRead = function (index) {
		var reminder = $scope.reminders[index];
		var dataObj = $.param({
			reminder_id: reminder.id
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'trivia/markreadreminder';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$scope.reminders.splice($scope.reminders.indexOf(reminder), 1);
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: $scope.lang.remindermarkasread,
						class_name: 'color success'
					});
					console.log(response);
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.InvoiceCancel = function (e) {
		var id = $(e.target).data('invoice');
		var dataObj = $.param({
			status_id: 4,
			invoice_id: id,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'invoices/cancelled';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: INVMARKCACELLED,
						class_name: 'color danger'
					});
					$('.toggle-due').hide();
					$('.toggle-cash').hide();
					$('.cancelledinvoicealert').show();
					console.log(response);
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.NotificationRead = function (index) {
		var notification = $scope.notifications[index];
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'trivia/mark_read_notification/' + notification.id;
		$http.post(posturl, config)
			.then(
				function (response) {
					console.log(response);
					window.location.href = notification.target;
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.ChangeTicketStatus = function () {
		var dataObj = $.param({
			statusid: $scope.item.code,
			ticketid: $(".tickid").val(),
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'tickets/chancestatus', dataObj, config)
			.then(
				function (response) {
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: TICKSTATUSCHANGE,
						class_name: 'color success'
					});
					$(".label-status").text($scope.item.name);
					console.log(response);
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.ciuisTooltip = {
		showTooltip: false,
		tipDirection: 'bottom'
	};

	$scope.ciuisTooltip.delayTooltip = undefined;

	$scope.$watch('demo.delayTooltip', function (val) {
		$scope.ciuisTooltip.delayTooltip = parseInt(val, 10) || 0;
	});

	$scope.passwordLength = 12;
	$scope.addUpper = true;
	$scope.addNumbers = true;
	$scope.addSymbols = true;

	function getRandomInt(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	function shuffleArray(array) {
		for (var i = array.length - 1; i > 0; i--) {
			var j = Math.floor(Math.random() * (i + 1));
			var temp = array[i];
			array[i] = array[j];
			array[j] = temp;
		}
		return array;
	}

	$scope.createPassword = function () {
		var lowerCharacters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
		var upperCharacters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
		var numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
		var symbols = ['!', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '-', '.', '/', ':', ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}', '~'];
		var noOfLowerCharacters = 0,
			noOfUpperCharacters = 0,
			noOfNumbers = 0,
			noOfSymbols = 0;
		var noOfneededTypes = $scope.addUpper + $scope.addNumbers + $scope.addSymbols;
		var noOfLowerCharacters = getRandomInt(1, $scope.passwordLength - noOfneededTypes);
		var usedTypeCounter = 1;
		if ($scope.addUpper) {
			noOfUpperCharacters = getRandomInt(1, $scope.passwordLength - noOfneededTypes + usedTypeCounter - noOfLowerCharacters);
			usedTypeCounter++;
		}
		if ($scope.addNumbers) {
			noOfNumbers = getRandomInt(1, $scope.passwordLength - noOfneededTypes + usedTypeCounter - noOfLowerCharacters - noOfUpperCharacters);
			usedTypeCounter++;
		}
		if ($scope.addSymbols) {
			noOfSymbols = $scope.passwordLength - noOfLowerCharacters - noOfUpperCharacters - noOfNumbers;
		}
		var passwordArray = [];
		for (var i = 0; i < noOfLowerCharacters; i++) {
			passwordArray.push(lowerCharacters[getRandomInt(1, lowerCharacters.length - 1)]);
		}
		for (var i = 0; i < noOfUpperCharacters; i++) {
			passwordArray.push(upperCharacters[getRandomInt(1, upperCharacters.length - 1)]);
		}
		for (var i = 0; i < noOfNumbers; i++) {
			passwordArray.push(numbers[getRandomInt(1, numbers.length - 1)]);
		}
		for (var i = 0; i < noOfSymbols; i++) {
			passwordArray.push(symbols[getRandomInt(1, symbols.length - 1)]);
		}
		passwordArray = shuffleArray(passwordArray);
		return passwordArray.join("");
	};

	$scope.passwordNew = $scope.createPassword();

	$scope.getNewPass = function () {
		$scope.passwordNew = $scope.createPassword();
	};
}

function Leads_Controller($scope, $http, $mdSidenav, $mdDialog, $mdConstant, $filter) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'lead').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$scope.toggleFilter = buildToggler('ContentFilter');
	$scope.LeadSettings = buildToggler('LeadsSettings');
	$scope.Create = buildToggler('Create');
	$scope.Import = buildToggler('Import');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();
		};
	}

	$scope.close = function () {
		$mdSidenav('ContentFilter').close();
		$mdSidenav('LeadsSettings').close();
		$mdSidenav('Create').close();
		$mdSidenav('Import').close();
		$mdDialog.hide();
	};

	$scope.ConvertedStatus = function (ev) {
		$mdDialog.show({
			templateUrl: 'converted-status-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.keys = [$mdConstant.KEY_CODE.ENTER, $mdConstant.KEY_CODE.COMMA];
	$scope.tags = [];
	var semicolon = 186;
	$scope.customKeys = [$mdConstant.KEY_CODE.ENTER, $mdConstant.KEY_CODE.COMMA, semicolon];

	$http.get(BASE_URL + 'api/settings_detail').then(function (Settings) {
		$scope.settings_detail = Settings.data;

		$scope.ConvertedLeadStatus = $scope.settings_detail.converted_lead_status_id;
		$scope.MakeConvertedLedStatus = function () {
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'leads/make_converted_status/' + $scope.ConvertedLeadStatus, config)
				.then(
					function (response) {
						console.log(response);
						$mdDialog.hide();
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.RemoveConverted = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title(MSG_TITLE)
				.textContent(MSG_REMOVE)
				.ariaLabel('Delete Converted Leads')
				.targetEvent($scope.ConvertedLeadStatus)
				.ok(MSG_OK)
				.cancel(MSG_CANCEL);

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'leads/remove_converted/' + $scope.ConvertedLeadStatus, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'leads';
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};
	});

	$http.get(BASE_URL + 'api/leads_by_leadsource_leadpage').then(function (LeadsBySource) {
		new Chart($('#leads_by_leadsource'), {
			type: 'horizontalBar',
			data: LeadsBySource.data,
			options: {
				legend: {
					display: false,
				}
			}
		});
	});

	$scope.leadsLoader = true;
	$http.get(BASE_URL + 'api/leads').then(function (Leads) {
		$scope.leads = Leads.data;
		$scope.leadsLoader = false;

		$scope.GoLeadDetail = function (index) {
			var lead = $scope.leads[index];
			window.location.href = BASE_URL + 'leads/lead/' + lead.id;
		};
		$scope.itemsPerPage = 5;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 5;
			var ps = [];
			var start;

			start = $scope.currentPage;
			//  console.log($scope.pageCount(),$scope.currentPage)
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};

		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};

		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};

		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};

		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};

		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};

		$scope.pageCount = function () {
			return Math.ceil($scope.leads.length / $scope.itemsPerPage) - 1;
		};
	});

	$scope.ShowKanban = function () {
		$scope.KanbanBoard = true;
	};

	$scope.HideKanban = function () {
		$scope.KanbanBoard = false;
	};

	$scope.dropSuccessHandler = function ($event, index, array) {
		$scope.selected_lead = $scope.leads[index];
		$scope.leads.splice($scope.leads.indexOf($scope.selected_lead), 1);
	};

	$scope.onDrop = function ($event, $data, array) {
		$scope.moved_lead = $data;
		var dataObj = $.param({
			lead_id: $scope.moved_lead.id,
			status_id: array,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'leads/move_lead/', dataObj, config)
			.then(
				function (response) {
					$http.get(BASE_URL + 'api/leads').then(function (Leads) {
						$scope.leads = Leads.data;
					});
				},
				function () {}
			);
	};

	$scope.saving = false;
	$scope.AddLead = function () {
		$scope.saving = true;
		$scope.tempArr = [];
		angular.forEach($scope.custom_fields, function (value) {
			if (value.type === 'input') {
				$scope.field_data = value.data;
			}
			if (value.type === 'textarea') {
				$scope.field_data = value.data;
			}
			if (value.type === 'date') {
				$scope.field_data = moment(value.data).format("YYYY-MM-DD");
			}
			if (value.type === 'select') {
				$scope.field_data = JSON.stringify(value.selected_opt);
			}
			$scope.tempArr.push({
				id: value.id,
				name: value.name,
				type: value.type,
				order: value.order,
				data: $scope.field_data,
				relation: value.relation,
				permission: value.permission,
				active: value.active,
			});
		});
		if (!$scope.lead) {
			var dataObj = $.param({
				title: '',
				date_contacted: '',
				name: '',
				company: '',
				assigned: '',
				status: '',
				source: '',
				phone: '',
				email: '',
				website: '',
				country_id: '',
				state: '',
				city: '',
				zip: '',
				address: '',
				description: '',
				public: '',
				type: '',
				tags: '',
				custom_fields: $scope.tempArr,
			});
		} else {
			if ($scope.lead.public === true) {
				$scope.isPublic = 1;
			} else {
				$scope.isPublic = 0;
			}
			if ($scope.lead.type === true) {
				$scope.isIndividual = 1;
			} else {
				$scope.isIndividual = 0;
			}
			if ($scope.lead.date_contacted) {
				$scope.lead.date_contacted = moment($scope.lead.date_contacted).format("YYYY-MM-DD HH:mm:ss");
			}
			var dataObj = $.param({
				title: $scope.lead.title,
				date_contacted: $scope.lead.date_contacted,
				name: $scope.lead.name,
				company: $scope.lead.company,
				assigned: $scope.lead.assigned_id,
				status: $scope.lead.status_id,
				source: $scope.lead.source_id,
				phone: $scope.lead.phone,
				email: $scope.lead.email,
				website: $scope.lead.website,
				country_id: $scope.lead.country_id,
				state: $scope.lead.state,
				city: $scope.lead.city,
				zip: $scope.lead.zip,
				address: $scope.lead.address,
				description: $scope.lead.description,
				public: $scope.isPublic,
				type: $scope.isIndividual,
				tags: JSON.stringify($scope.tags),
				custom_fields: $scope.tempArr,
			});
		}
			
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'leads/create/';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$scope.saving = false;
					if (response.data.success == true) {
						window.location.href = BASE_URL + 'leads/lead/' + response.data.id;
					} else {
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.saving = false;
				}
			);
	};

	$scope.filter = {};
	$scope.getOptionsFor = function (propName) {
		return ($scope.leads || []).map(function (item) {
			return item[propName];
		}).filter(function (item, idx, arr) {
			return arr.indexOf(item) === idx;
		}).sort();
	};
	$scope.FilteredData = function (item) {
		// Use this snippet for matching with AND
		var matchesAND = true;
		for (var prop in $scope.filter) {
			if (noSubFilter($scope.filter[prop])) {
				continue;
			}
			if (!$scope.filter[prop][item[prop]]) {
				matchesAND = false;
				break;
			}
		}
		return matchesAND;
	};

	function noSubFilter(subFilterObj) {
		for (var key in subFilterObj) {
			if (subFilterObj[key]) {
				return false;
			}
		}
		return true;
	}
	// Filtered Datas
	$scope.search = {
		name: '',
		statusname: ''
	};

	$http.get(BASE_URL + 'api/leadstatuses').then(function (LeadStatuses) {
		$scope.leadstatuses = LeadStatuses.data;
		$scope.NewStatus = function () {
			var confirm = $mdDialog.prompt()
				.title($scope.lang.new_status)
				.textContent($scope.lang.type_status_name)
				.placeholder($scope.lang.status_name)
				.ariaLabel($scope.lang.status_name)
				.initialValue('')
				.required(true)
				.ok($scope.lang.add)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'leads/add_status/', dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$scope.leadstatuses.push({
								'id': response.data,
								'name': result,
							});
						},
						function (response) {
							console.log(response);
						}
					);
			}, function () {

			});
		};
		$scope.EditStatus = function (status_id, lead_status, event) {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.prompt()
				.title('Edit Lead Status')
				.textContent('You can change lead status name.')
				.placeholder('Status name')
				.ariaLabel('Status Name')
				.initialValue(lead_status)
				.targetEvent(event)
				.required(true)
				.ok('Save')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'leads/update_status/' + status_id, dataObj, config)
					.then(
						function () {
							//Success
						},
						function () {
							//UNSUCCESS
						}
					);
			}, function () {
				//Cancel
			});
		};
		$scope.DeleteLeadStatus = function (index) {
			var status = $scope.leadstatuses[index];
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'leads/remove_status/' + status.id, config)
				.then(
					function (response) {
						$scope.leadstatuses.splice($scope.leadstatuses.indexOf(status), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/leadsources').then(function (LeadSources) {
		$scope.leadssources = LeadSources.data;
		$scope.NewSource = function () {
			var confirm = $mdDialog.prompt()
				.title($scope.lang.new_source)
				.textContent($scope.lang.type_source_name)
				.placeholder($scope.lang.source_name)
				.ariaLabel($scope.lang.source_name)
				.initialValue('')
				.required(true)
				.ok($scope.lang.add)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'leads/add_source/', dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$scope.leadssources.push({
								'id': response.data,
								'name': result,
							});
						},
						function (response) {
							console.log(response);
						}
					);
			}, function () {

			});
		};
		$scope.EditSource = function (source_id, lead_source, event) {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.prompt()
				.title('Edit Lead Source')
				.textContent('You can change lead source name.')
				.placeholder('Status name')
				.ariaLabel('Status Name')
				.initialValue(lead_source)
				.targetEvent(event)
				.required(true)
				.ok('Save')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'leads/update_source/' + source_id, dataObj, config)
					.then(
						function () {
							//Success
						},
						function () {
							//UNSUCCESS
						}
					);
			}, function () {
				//Cancel
			});
		};
		$scope.DeleteLeadSource = function (index) {
			var source = $scope.leadssources[index];
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'leads/remove_source/' + source.id, config)
				.then(
					function (response) {
						$scope.leadssources.splice($scope.leadssources.indexOf(source), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});
}

function Lead_Controller($scope, $http, $mdSidenav, $mdDialog, $filter) {
	"use strict";

	$scope.ReminderForm = buildToggler('ReminderForm');
	$scope.Update = buildToggler('Update');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}
	$scope.close = function () {
		$mdSidenav('ReminderForm').close();
		$mdSidenav('Update').close();
	};

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'lead/' + LEADID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$scope.leadsLoader = true;
	$http.get(BASE_URL + 'api/lead/' + LEADID).then(function (Lead) {
		$scope.lead = Lead.data;
		$scope.leadsLoader = false;

		$scope.MarkLeadAs = function (status) {
			if (status === 1) {
				$scope.lead.lost = 1;
				$scope.valuOn = 1;
				$scope.TypeOn = 'lost';
			}
			if (status === 2) {
				$scope.lead.lost = 0;
				$scope.valuOn = 2;
				$scope.TypeOn = 'lost';
			}
			if (status === 3) {
				$scope.lead.junk = 1;
				$scope.valuOn = 3;
				$scope.TypeOn = 'junk';
			}
			if (status === 4) {
				$scope.lead.junk = 0;
				$scope.valuOn = 4;
				$scope.TypeOn = 'junk';
			}
			var dataObj = $.param({
				value: $scope.valuOn,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'leads/mark_as_lead/' + LEADID, dataObj, config)
				.then(
					function (response) {
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.saving = false;
		$scope.UpdateLead = function () {
			$scope.saving = true;
			if ($scope.lead.public === true) {
				$scope.isPublic = 1;
			} else {
				$scope.isPublic = 0;
			}
			if ($scope.lead.type === true) {
				$scope.isIndividual = 1;
			} else {
				$scope.isIndividual = 0;
			}
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			var dataObj = $.param({
				title: $scope.lead.title,
				name: $scope.lead.name,
				company: $scope.lead.company,
				assigned_id: $scope.lead.assigned_id,
				status: $scope.lead.status_id,
				source: $scope.lead.source_id,
				phone: $scope.lead.phone,
				email: $scope.lead.email,
				website: $scope.lead.website,
				country_id: $scope.lead.country_id,
				state: $scope.lead.state,
				city: $scope.lead.city,
				zip: $scope.lead.zip,
				address: $scope.lead.address,
				description: $scope.lead.description,
				public: $scope.isPublic,
				type: $scope.isIndividual,
				custom_fields: $scope.tempArr,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'leads/update/' + LEADID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.saving = false;
						if (response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							$mdSidenav('Update').close();
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.saving = false;
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/leadstatuses').then(function (LeadStatuses) {
		$scope.statuses = LeadStatuses.data;
	});

	$http.get(BASE_URL + 'api/leadsources').then(function (LeadSources) {
		$scope.sources = LeadSources.data;
	});

	$scope.Delete = function () {
		// Appending dialog to document.body to cover sidenav in docs app
		var confirm = $mdDialog.confirm()
			.title('Attention!')
			.textContent('Do you confirm the deletion of all data belonging to this lead?')
			.ariaLabel('Delete Customer')
			.targetEvent(LEADID)
			.ok('Do it!')
			.cancel('Cancel');

		$mdDialog.show(confirm).then(function () {
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'leads/remove/' + LEADID, config)
				.then(
					function (response) {
						console.log(response);
						window.location.href = BASE_URL + 'leads';
					},
					function (response) {
						console.log(response);
					}
				);

		}, function () {
			//
		});
	};

	$scope.Convert = function () {
		// Appending dialog to document.body to cover sidenav in docs app
		var confirm = $mdDialog.confirm()
			.title('Convert Lead to Customer!')
			.textContent('Do you want to convert lead into a customer?')
			.ariaLabel('Convert Lead')
			.targetEvent(LEADID)
			.ok('Convert!')
			.cancel('Cancel');

		$mdDialog.show(confirm).then(function () {
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'leads/convert/' + LEADID, config)
				.then(
					function (response) {
						console.log(response);
						if (response.data === false) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: 'Already converted!',
								class_name: 'color warning'
							});
						} else {
							window.location.href = BASE_URL + 'customers/customer/' + response.data;
						}
					}
				);

		}, function () {
			//
		});
	};

	$http.get(BASE_URL + 'api/reminders_by_type/lead/' + LEADID).then(function (Reminders) {
		$scope.in_reminders = Reminders.data;
		$scope.AddReminder = function () {
			var dataObj = $.param({
				description: $scope.reminder_description,
				date: moment($scope.reminder_date).format("YYYY-MM-DD HH:mm:ss"),
				staff: $scope.reminder_staff,
				relation_type: 'lead',
				relation: LEADID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/addreminder';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$scope.in_reminders.push({
							'description': $scope.reminder_description,
							'creator': LOGGEDINSTAFFNAME,
							'avatar': UPIMGURL + LOGGEDINSTAFFAVATAR,
							'staff': LOGGEDINSTAFFNAME,
							'date': $scope.reminder_date,
						});
						$mdSidenav('ReminderForm').close();
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.DeleteReminder = function (index) {
			var reminder = $scope.in_reminders[index];
			var dataObj = $.param({
				reminder: reminder.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/remove_reminder';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.in_reminders.splice($scope.in_reminders.indexOf(reminder), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/notes/lead/' + LEADID).then(function (Notes) {
		$scope.notes = Notes.data;
		$scope.AddNote = function () {
			var dataObj = $.param({
				description: $scope.note,
				relation_type: 'lead',
				relation: LEADID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/addnote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$('.note-description').val('');
							$scope.note = '';
							$http.get(BASE_URL + 'api/notes/lead/' + LEADID).then(function (Notes) {
								$scope.notes = Notes.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.DeleteNote = function (index) {
			var note = $scope.notes[index];
			var dataObj = $.param({
				notes: note.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/removenote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.notes.splice($scope.notes.indexOf(note), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/proposals').then(function (Proposals) {
		$scope.all_proposals = Proposals.data;
		$scope.proposals = $filter('filter')($scope.all_proposals, {
			relation_type: "lead",
			relation: LEADID
		});
	});
}

function Accounts_Controller($scope, $http, $mdSidenav) {
	"use strict";

	$scope.Create = buildToggler('Create');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}
	$scope.close = function () {
		$mdSidenav('Create').close();
	};

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
		$scope.AddAccount = function () {
			if ($scope.isBankType === true) {
				$scope.isBank = 1;
			}
			if ($scope.isBankType === false) {
				$scope.isBank = 0;
			}
			var dataObj = $.param({
				name: $scope.account.name,
				bankname: $scope.account.bankname,
				branchbank: $scope.account.branchbank,
				account: $scope.account.account,
				iban: $scope.account.iban,
				type: $scope.isBank,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'accounts/create/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$mdSidenav('Create').close();
						$scope.accounts.push({
							'id': response.data.id,
							'name': response.data.name,
							'amount': response.data.amount,
							'icon': response.data.icon,
							'status': response.data.status,
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});
}

function Account_Controller($scope, $http, $mdSidenav, $mdDialog) {
	"use strict";

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});

	$scope.QuickTransfer_ = false;

	$scope.QuickTransfer = function () {
		$scope.QuickTransfer_ = true;
	};

	$scope.CancelTransfer = function () {
		$scope.QuickTransfer_ = false;
	};

	$scope.Update = buildToggler('Update');

	$scope.Detail = function (id) {
		$mdDialog.show({
			contentElement: '#payment-' + id,
			parent: angular.element(document.body),
			targetEvent: id,
			clickOutsideToClose: true
		});
	};

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('Update').close();
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'api/account/' + ACCOUNTID).then(function (Account) {
		$scope.account = Account.data;

		$scope.MakeTransfer = function () {
			var dataObj = $.param({
				from_account_id: $scope.account.id,
				to_account_id: $scope.To_Account_ID,
				amount: $scope.TransferAmount,
			});

			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'accounts/make_transfer';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$mdSidenav('EventForm').close();
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: $scope.transfer_message,
							position: 'bottom',
							class_name: 'color success',
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.current_balance = $scope.account.account_total;

		if ($scope.account.status === true) {
			$scope.isActive = 0;
		} else {
			$scope.isActive = 1;
		}
		$scope.UpdateAccount = function () {
			var dataObj = $.param({
				name: $scope.account.name,
				bankname: $scope.account.bankname,
				branchbank: $scope.account.branchbank,
				account: $scope.account.account,
				iban: $scope.account.iban,
				status: $scope.isActive,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'accounts/update/' + ACCOUNTID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$mdSidenav('Update').close();
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: response.data,
							class_name: 'color success'
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.Delete = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title('Attention!')
				.textContent('Do you confirm the deletion of all data belonging to this account?')
				.ariaLabel('Delete Account')
				.targetEvent(ACCOUNTID)
				.ok('Do it!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'accounts/remove/' + ACCOUNTID, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'accounts';
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};
	});
}

function Customers_Controller($scope, $http, $mdSidenav, $filter) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'customer').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$scope.customersLoader = true;

	$scope.toggleFilter = buildToggler('ContentFilter');
	$scope.Create = buildToggler('Create');
	$scope.ImportCustomersNav = buildToggler('ImportCustomersNav');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}
	$scope.close = function () {
		$mdSidenav('ContentFilter').close();
		$mdSidenav('Create').close();
		$mdSidenav('ImportCustomersNav').close();
	};

	$http.get(BASE_URL + 'api/customers').then(function (Customers) {
		$scope.customers = Customers.data;
		$scope.customersLoader = false;

		$scope.GoCustomer = function (index) {
			var customer = $scope.customers[index];
			window.location.href = BASE_URL + 'customers/customer/' + customer.id;
		};

		$scope.isIndividual = false;
		$scope.saving = false;
		$scope.AddCustomer = function () {
			$scope.saving = true;
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			if (!$scope.customer) {
				var dataObj = $.param({
					company: '',
					namesurname: '',
					taxoffice: '',
					taxnumber: '',
					ssn: '',
					executive: '',
					address: '',
					zipcode: '',
					country_id: '',
					state: '',
					city: '',
					town: '',
					phone: '',
					fax: '',
					email: '',
					web: '',
					risk: '',
					billing_street: '',
					billing_city: '',
					billing_state: '',
					billing_zip: '',
					billing_country: '',
					shipping_street: '',
					shipping_city: '',
					shipping_state: '',
					shipping_zip: '',
					shipping_country: '',
					type: '',
					custom_fields: $scope.tempArr,
					default_payment_method: ''
				});
			} else {
				var dataObj = $.param({
					company: $scope.customer.company,
					namesurname: $scope.customer.namesurname,
					taxoffice: $scope.customer.taxoffice,
					taxnumber: $scope.customer.taxnumber,
					ssn: $scope.customer.ssn,
					executive: $scope.customer.executive,
					address: $scope.customer.address,
					zipcode: $scope.customer.zipcode,
					country_id: $scope.customer.country_id,
					state: $scope.customer.state,
					city: $scope.customer.city,
					town: $scope.customer.town,
					phone: $scope.customer.phone,
					fax: $scope.customer.fax,
					email: $scope.customer.email,
					web: $scope.customer.web,
					risk: $scope.customer.risk,
					billing_street: $scope.customer.billing_street,
					billing_city: $scope.customer.billing_city,
					billing_state: $scope.customer.billing_state,
					billing_zip: $scope.customer.billing_zip,
					billing_country: $scope.customer.billing_country,
					shipping_street: $scope.customer.shipping_street,
					shipping_city: $scope.customer.shipping_city,
					shipping_state: $scope.customer.shipping_state,
					shipping_zip: $scope.customer.shipping_zip,
					shipping_country: $scope.customer.shipping_country,
					type: $scope.isIndividual,
					custom_fields: $scope.tempArr,
					default_payment_method: $scope.customer.default_payment_method
				});
			}
				
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'customers/create/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							window.location.href = BASE_URL + 'customers/customer/' + response.data.id;
						} else {
							$scope.saving = false;
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.saving = false;
						$http.get(BASE_URL + 'api/customers').then(function (Customers) {
							$scope.customers = Customers.data;
						});
					}
				);
		};

		$scope.SameAsCustomerAddress = function () {
			$scope.customer.billing_street = $scope.customer.address;
			$scope.customer.billing_city = $scope.customer.city;
			$scope.customer.billing_state = $scope.customer.state;
			$scope.customer.billing_zip = $scope.customer.zipcode;
			$scope.customer.billing_country = $scope.customer.country_id;
		};

		$scope.SameAsBillingAddress = function () {
			$scope.customer.shipping_street = $scope.customer.billing_street;
			$scope.customer.shipping_city = $scope.customer.billing_city;
			$scope.customer.shipping_state = $scope.customer.billing_state;
			$scope.customer.shipping_zip = $scope.customer.billing_zip;
			$scope.customer.shipping_country = $scope.customer.billing_country;
		};

		$scope.filter = {};
		$scope.getOptionsFor = function (propName) {
			return ($scope.customers || []).map(function (item) {
				return item[propName];
			}).filter(function (item, idx, arr) {
				return arr.indexOf(item) === idx;
			}).sort();
		};
		$scope.FilteredData = function (item) {
			// Use this snippet for matching with AND
			var matchesAND = true;
			for (var prop in $scope.filter) {
				if (noSubFilter($scope.filter[prop])) {
					continue;
				}
				if (!$scope.filter[prop][item[prop]]) {
					matchesAND = false;
					break;
				}
			}
			return matchesAND;

		};

		function noSubFilter(subFilterObj) {
			for (var key in subFilterObj) {
				if (subFilterObj[key]) {
					return false;
				}
			}
			return true;
		}
		$scope.updateDropdown = function (_prop) {
			var _opt = this.filter_select,
				_optList = this.getOptionsFor(_prop),
				len = _optList.length;

			if (_opt == 'all') {
				for (var j = 0; j < len; j++) {
					$scope.filter[_prop][_optList[j]] = true;
				}
			} else {
				for (var j = 0; j < len; j++) {
					$scope.filter[_prop][_optList[j]] = false;
				}
				$scope.filter[_prop][_opt] = true;
			}
		};
		// Filtered Datas
		$scope.search = {
			name: '',
			phone: '',
			email: '',
		};
		$scope.itemsPerPage = 5;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 5;
			var ps = [];
			var start;

			start = $scope.currentPage;
			//  console.log($scope.pageCount(),$scope.currentPage)
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};

		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};

		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};

		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};

		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};

		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};

		$scope.pageCount = function () {
			return Math.ceil($scope.customers.length / $scope.itemsPerPage) - 1;
		};
	});
}

function Customer_Controller($scope, $http, $filter, $mdSidenav, $mdDialog) {
	"use strict";

	$scope.ReminderForm = buildToggler('ReminderForm');
	$scope.NewContact = buildToggler('NewContact');
	$scope.Update = buildToggler('Update');
	$('.update-view').hide();

	$scope.customersLoader = true;

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('ReminderForm').close();
		$mdSidenav('NewContact').close();
		$mdSidenav('Update').close();
	};

	$scope.SameAsCustomerAddress = function () {
		$scope.customer.billing_street = $scope.customer.address;
		$scope.customer.billing_city = $scope.customer.city;
		$scope.customer.billing_state = $scope.customer.state;
		$scope.customer.billing_zip = $scope.customer.zipcode;
		$scope.customer.billing_country = $scope.customer.country_id;
	};

	$scope.SameAsBillingAddress = function () {
		$scope.customer.shipping_street = $scope.customer.billing_street;
		$scope.customer.shipping_city = $scope.customer.billing_city;
		$scope.customer.shipping_state = $scope.customer.billing_state;
		$scope.customer.shipping_zip = $scope.customer.billing_zip;
		$scope.customer.shipping_country = $scope.customer.billing_country;
	};

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'customer/' + CUSTOMERID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$http.get(BASE_URL + 'api/customer/' + CUSTOMERID).then(function (Customer) {
		$scope.customer = Customer.data;
		$scope.customersLoader = false;

		var canvas = document.getElementById("customer_annual_sales_chart");
		var multiply = {
			beforeDatasetsDraw: function (chart, options, el) {
				chart.ctx.globalCompositeOperation = 'multiply';
			},
			afterDatasetsDraw: function (chart, options) {
				chart.ctx.globalCompositeOperation = 'source-over';
			},
		};
		var gradientThisWeek = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
		gradientThisWeek.addColorStop(0, '#ffbc00');
		gradientThisWeek.addColorStop(1, '#fff');
		var gradientPrevWeek = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
		gradientPrevWeek.addColorStop(0, '#616f8c');
		gradientPrevWeek.addColorStop(1, '#fff');
		var config = {
			type: 'bar',
			data: $scope.customer.chart_data,
			options: {
				elements: {
					point: {
						radius: 0,
						hitRadius: 5,
						hoverRadius: 5
					}
				},
				legend: {
					display: false,
				},
				scales: {
					xAxes: [{
						display: false,
					}],
					yAxes: [{
						display: false,
						ticks: {
							beginAtZero: true,
						},
					}]
				},
				legend: {
					display: true
				}
			},
			plugins: [multiply],
		};
		window.chart = new Chart(canvas, config)

		$http.get(BASE_URL + 'api/invoices').then(function (Invoices) {
			$scope.all_invoices = Invoices.data;
			$scope.invoices = $filter('filter')($scope.all_invoices, {
				customer_id: CUSTOMERID,
			});
		});

		$scope.GoInvoice = function (index) {
			var invoice = $scope.invoices[index];
			window.location.href = BASE_URL + 'invoices/invoice/' + invoice.id;
		};

		$http.get(BASE_URL + 'api/proposals').then(function (Proposals) {
			$scope.all_proposals = Proposals.data;
			$scope.proposals = $filter('filter')($scope.all_proposals, {
				relation_type: 'customer',
				relation: CUSTOMERID,
			});
		});

		$scope.GoProposal = function (index) {
			var proposal = $scope.proposals[index];
			window.location.href = BASE_URL + 'proposals/proposal/' + proposal.id;
		};

		$http.get(BASE_URL + 'api/projects').then(function (Projects) {
			$scope.all_projects = Projects.data;
			$scope.projects = $filter('filter')($scope.all_projects, {
				customer_id: CUSTOMERID,
			});
		});

		$scope.GoProject = function (index) {
			var project = $scope.projects[index];
			window.location.href = BASE_URL + 'projects/project/' + project.id;
		};

		$http.get(BASE_URL + 'api/tickets').then(function (Tickets) {
			$scope.all_tickets = Tickets.data;
			$scope.tickets = $filter('filter')($scope.all_tickets, {
				customer_id: CUSTOMERID,
			});
		});

		$scope.GoTicket = function (index) {
			var ticket = $scope.tickets[index];
			window.location.href = BASE_URL + 'tickets/ticket/' + ticket.id;
		};

		$scope.savingCustomer = false;
		$scope.UpdateCustomer = function () {
			$scope.savingCustomer = true;
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			var dataObj = $.param({
				company: $scope.customer.company,
				namesurname: $scope.customer.namesurname,
				taxoffice: $scope.customer.taxoffice,
				taxnumber: $scope.customer.taxnumber,
				ssn: $scope.customer.ssn,
				executive: $scope.customer.executive,
				address: $scope.customer.address,
				zipcode: $scope.customer.zipcode,
				country_id: $scope.customer.country_id,
				state: $scope.customer.state,
				city: $scope.customer.city,
				town: $scope.customer.town,
				phone: $scope.customer.phone,
				fax: $scope.customer.fax,
				email: $scope.customer.email,
				web: $scope.customer.web,
				risk: $scope.customer.risk,
				billing_street: $scope.customer.billing_street,
				billing_city: $scope.customer.billing_city,
				billing_state: $scope.customer.billing_state,
				billing_zip: $scope.customer.billing_zip,
				billing_country: $scope.customer.billing_country,
				shipping_street: $scope.customer.shipping_street,
				shipping_city: $scope.customer.shipping_city,
				shipping_state: $scope.customer.shipping_state,
				shipping_zip: $scope.customer.shipping_zip,
				shipping_country: $scope.customer.shipping_country,
				custom_fields: $scope.tempArr,
				default_payment_method: $scope.customer.default_payment_method,
				type: $scope.customer.isIndividual
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'customers/customer/' + CUSTOMERID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.savingCustomer = false;
						if (response.data.success == true) {
							$mdSidenav('Update').close();
							showToast(NTFTITLE, response.data.message, ' success');
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.savingCustomer = false;
					}
				);
		};

		$scope.Delete = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title('Attention!')
				.textContent('Do you confirm the deletion of all data belonging to this customer?')
				.ariaLabel('Delete Customer')
				.targetEvent(CUSTOMERID)
				.ok('Do it!')
				.cancel('Cancel'); 

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'customers/remove/' + CUSTOMERID, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'customers';
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

		$http.get(BASE_URL + 'api/contact/' + CUSTOMERID).then(function (contact) {
			$scope.all_contacts = contact.data;
			$scope.contacts = $filter('filter')($scope.all_contacts, {
				customer_id: CUSTOMERID,
			});
		});
		// $http.get(BASE_URL + 'api/contacts').then(function (contacts) {
		// 	$scope.all_contacts = contacts.data;
		// 	$scope.contacts = $filter('filter')($scope.all_contacts, {
		// 		customer_id: CUSTOMERID,
		// 	});
		// });

		$scope.isPrimary = false;
		$scope.isAdmin = false;

		$scope.ContactDetail = function (index) {
			var contact = $scope.contacts[index];
			$mdDialog.show({
				contentElement: '#ContactModal-' + contact.id,
				parent: angular.element(document.body),
				targetEvent: index,
				clickOutsideToClose: true
			});
			$scope.UpdateContactPrivilege = function (id, value, privilege_id) {
				$http.post(BASE_URL + 'customers/update_contact_privilege/' + id + '/' + value + '/' + privilege_id)
					.then(
						function (response) {
						},
						function (response) {
							console.log(response);
						}
					);
			};
		};

		$scope.saving = false;
		$scope.AddContact = function () {
			$scope.saving = true;
			var dataObj = $.param({
				name: $scope.newcontact.name,
				surname: $scope.newcontact.surname,
				phone: $scope.newcontact.phone,
				extension: $scope.newcontact.extension,
				mobile: $scope.newcontact.mobile,
				email: $scope.newcontact.email,
				address: $scope.newcontact.address,
				skype: $scope.newcontact.skype,
				linkedin: $scope.newcontact.linkedin,
				position: $scope.newcontact.position,
				customer: CUSTOMERID,
				isPrimary: $scope.isPrimary,
				isAdmin: $scope.isAdmin,
				password: $scope.passwordNew,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'customers/create_contact/', dataObj, config)
				.then(
					function (response) {
						$scope.saving = false;
						$mdSidenav('NewContact').close();
						$http.get(BASE_URL + 'api/contact/' + CUSTOMERID).then(function (contact) {
							$scope.all_contacts = contact.data;
							$scope.contacts = $filter('filter')($scope.all_contacts, {
								customer_id: CUSTOMERID,
							});
						});
						
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: response.data,
							class_name: 'color success'
						});
					},
					function (response) {
						$scope.saving = false;
					}
				);
		};

		$scope.UpdateContact = function (index) {
			var contact = $scope.contacts[index];
			var contact_id = contact.id;
			$scope.contact = contact;
			var dataObj = $.param({
				name: $scope.contact.name,
				surname: $scope.contact.surname,
				phone: $scope.contact.phone,
				extension: $scope.contact.extension,
				mobile: $scope.contact.mobile,
				email: $scope.contact.email,
				address: $scope.contact.address,
				skype: $scope.contact.skype,
				linkedin: $scope.contact.linkedin,
				position: $scope.contact.position,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'customers/update_contact/' + contact_id;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$mdDialog.cancel();
						showToast(NTFTITLE, response.data.message, 'success');
						$('#updatecontact' + contact_id + '').modal('hide');
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.ChangePassword = function (contact) {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.prompt()
				.title('Change Password')
				.textContent('Are sure change contact password?')
				.placeholder('Password')
				.ariaLabel('Password')
				.initialValue('')
				.targetEvent(contact)
				.required(true)
				.ok('Okay!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					password: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'customers/change_password_contact/' + contact, dataObj, config)
					.then(
						function (response) {
							showToast(NTFTITLE, response.data, ' success');
						},
						function (response) {
						}
					);
			}, function () {

			});
		};

		$scope.RemoveContact = function (id) {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title('Attention!')
				.textContent('Are you sure you want to delete this contact from contacts?')
				.ariaLabel('Delete Contact')
				.targetEvent(id)
				.ok('Please do it!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'customers/remove_contact/' + id, config)
					.then(
						function (response) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data,
								class_name: 'color danger'
							});
							$scope.contacts.splice($scope.contacts.indexOf(id), 1);
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

		$scope.CloseModal = function () {
			$mdDialog.cancel();
		};

	});

	$http.get(BASE_URL + 'api/reminders_by_type/customer/' + CUSTOMERID).then(function (Reminders) {
		$scope.in_reminders = Reminders.data;

		$scope.AddReminder = function () {
			var dataObj = $.param({
				description: $scope.reminder_description,
				date: moment($scope.reminder_date).format("YYYY-MM-DD HH:mm:ss"),
				staff: $scope.reminder_staff,
				relation_type: 'customer',
				relation: CUSTOMERID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/addreminder';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$scope.in_reminders.push({
							'description': $scope.reminder_description,
							'creator': LOGGEDINSTAFFNAME,
							'avatar': UPIMGURL + LOGGEDINSTAFFAVATAR,
							'staff': LOGGEDINSTAFFNAME,
							'date': $scope.reminder_date,
						});
						$mdSidenav('ReminderForm').close();
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.DeleteReminder = function (index) {
			var reminder = $scope.in_reminders[index];
			var dataObj = $.param({
				reminder: reminder.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/remove_reminder';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.in_reminders.splice($scope.in_reminders.indexOf(reminder), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/notes/customer/' + CUSTOMERID).then(function (Notes) {
		$scope.notes = Notes.data;

		$scope.AddNote = function () {
			var dataObj = $.param({
				description: $scope.note,
				relation_type: 'customer',
				relation: CUSTOMERID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/addnote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$('.note-description').val('');
							$scope.note = '';
							$http.get(BASE_URL + 'api/notes/customer/' + CUSTOMERID).then(function (Notes) {
								$scope.notes = Notes.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.DeleteNote = function (index) {
			var note = $scope.notes[index];
			var dataObj = $.param({
				notes: note.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/removenote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.notes.splice($scope.notes.indexOf(note), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});
}

function Tasks_Controller($scope, $http, $mdSidenav, $filter) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'task').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$scope.toggleFilter = buildToggler('ContentFilter');
	$scope.Create = buildToggler('Create');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}
	$scope.close = function () {
		$mdSidenav('ContentFilter').close();
		$mdSidenav('Create').close();
	};

	$http.get(BASE_URL + 'api/tasks').then(function (Tasks) {
		$scope.tasks = Tasks.data;

		$scope.Relation_Type = 'project';

		$scope.AddTask = function () {
			if ($scope.isPublic === true) {
				$scope.isPublicValue = 1;
			} else {
				$scope.isPublicValue = 0;
			}
			if ($scope.isBillable === true) {
				$scope.isBillableValue = 1;
			} else {
				$scope.isBillableValue = 0;
			}
			if ($scope.isVisible === true) {
				$scope.isVisibleValue = 1;
			} else {
				$scope.isVisibleValue = 0;
			}
			if ($scope.Relation_Type === 'project') {
				$scope.related_with = $scope.RelatedProject.id;
			}
			if ($scope.Relation_Type === 'ticket') {
				$scope.related_with = $scope.RelatedTicket.id;
			}
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			var dataObj = $.param({
				name: $scope.task.name,
				hourly_rate: $scope.task.hourlyrate,
				assigned: $scope.task.assigned,
				priority: $scope.task.priority_id,
				relation_type: $scope.Relation_Type,
				relation: $scope.related_with,
				milestone: $scope.SelectedMilestone,
				status_id: $scope.task.status_id,
				public: $scope.isPublicValue,
				billable: $scope.isBillableValue,
				visible: $scope.isVisibleValue,
				startdate: moment($scope.task.startdate).format("YYYY-MM-DD"),
				duedate: moment($scope.task.duedate).format("YYYY-MM-DD"),
				description: $scope.task.description,
				custom_fields: $scope.tempArr,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'tasks/create/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						window.location.href = BASE_URL + 'tasks/task/' + response.data;
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.filter = {};
		$scope.getOptionsFor = function (propName) {
			return ($scope.tasks || []).map(function (item) {
				return item[propName];
			}).filter(function (item, idx, arr) {
				return arr.indexOf(item) === idx;
			}).sort();
		};

		$scope.FilteredData = function (item) {
			// Use this snippet for matching with AND
			var matchesAND = true;
			for (var prop in $scope.filter) {
				if (noSubFilter($scope.filter[prop])) {
					continue;
				}
				if (!$scope.filter[prop][item[prop]]) {
					matchesAND = false;
					break;
				}
			}
			return matchesAND;

		};

		function noSubFilter(subFilterObj) {
			for (var key in subFilterObj) {
				if (subFilterObj[key]) {
					return false;
				}
			}
			return true;
		}
		// Filtered Datas
		$scope.search = {
			name: '',
		};
		$scope.itemsPerPage = 5;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 5;
			var ps = [];
			var start;

			start = $scope.currentPage;
			//  console.log($scope.pageCount(),$scope.currentPage)
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};

		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};

		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};

		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};

		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};

		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};

		$scope.pageCount = function () {
			return Math.ceil($scope.tasks.length / $scope.itemsPerPage) - 1;
		};

	});

	$http.get(BASE_URL + 'api/projects').then(function (Projects) {
		$scope.projects = Projects.data;
	});

	$http.get(BASE_URL + 'api/milestones').then(function (Milestones) {
		$scope.milestones = Milestones.data;
	});
}

function Task_Controller($scope, $http, $mdSidenav, $mdDialog) {
	"use strict";

	$scope.Update = buildToggler('Update');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('Update').close();
		$mdDialog.hide();
	};

	$scope.title = 'Sub Tasks';

	$scope.UploadFile = function (ev) {
		$mdDialog.show({
			templateUrl: 'addfile-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'task/' + TASKID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$http.get(BASE_URL + 'api/task/' + TASKID).then(function (Task) {
		$scope.task = Task.data;

		$http.get(BASE_URL + 'api/project/' + $scope.task.relation).then(function (Project) {
			$scope.task.project_data = Project.data;
		});

		$scope.startTimerforTask = function () {
			var dataObj = $.param({
				task: TASKID,
				project: $scope.task.relation,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'tasks/starttimer', dataObj, config)
				.then(
					function (response) {
						$scope.task.timer = true;
						showToast(NTFTITLE, response.data.message, 'success');
						$('#stopTaskTimer').attr('style','display: block !important');
						$('#startTaskTimer').css('display', 'none');
						$scope.timer = {};
						$scope.timer.loading = true;
						$scope.timer.start = false;
						$scope.timer.stop = true;
						$scope.timer.found = false;
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.stopTimer = function () {
			$mdDialog.show({
		      	templateUrl: 'stopTimer.html',
		      	parent: angular.element(document.body),
		      	clickOutsideToClose: false,
		      	fullscreen: false,
		      	escapeToClose: false,
		      	controller: NewTeamDialogController,
		    });
		};

		function NewTeamDialogController($scope, $mdDialog) {  
			$scope.stopTimerforTask = function () {
				var note;
				if (!$scope.stopTimer) {
					note = '';
				} else {
					note = $scope.stopTimer.note;
				}
				var dataObj = $.param({
					task: TASKID,
					note: note
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'tasks/stoptimer', dataObj, config)
					.then(
						function (response) {
							$mdDialog.hide();
							showToast(NTFTITLE, response.data.message, 'success');
							$('#stopTaskTimer').css('display', 'none');
							$('#startTaskTimer').attr('style','display: block !important');
							if (TASKID == $scope.timer.task_id) {
								$('#timerStarted').removeClass('text-success');
								$('#timerStarted').addClass('text-muted');
							}
						},
						function (response) {
							console.log(response);
						}
					);
			};
			$scope.close = function(){$mdDialog.hide();}
		}


		$scope.UpdateTask = function () {
			if ($scope.task.public === true) {
				$scope.isPublic = 1;
			} else {
				$scope.isPublic = 0;
			}
			if ($scope.task.visible === true) {
				$scope.isVisible = 1;
			} else {
				$scope.isVisible = 0;
			}
			if ($scope.task.billable === true) {
				$scope.isBillable = 1;
			} else {
				$scope.isBillable = 0;
			}
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			var dataObj = $.param({
				name: $scope.task.name,
				hourly_rate: $scope.task.hourlyrate,
				assigned: $scope.task.assigned,
				priority: $scope.task.priority_id,
				relation_type: $scope.task.relation_type,
				relation: $scope.task.relation,
				milestone: $scope.task.milestone,
				status_id: $scope.task.status_id,
				public: $scope.isPublic,
				billable: $scope.isBillable,
				visible: $scope.isVisible,
				startdate: moment($scope.task.startdate).format("YYYY-MM-DD"),
				duedate: moment($scope.task.duedate).format("YYYY-MM-DD"),
				description: $scope.task.description,
				custom_fields: $scope.tempArr,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'tasks/update/' + TASKID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$mdSidenav('Update').close();
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.Delete = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title('Attention!')
				.textContent('Do you confirm the deletion of all data belonging to this task?')
				.ariaLabel('Delete Task')
				.targetEvent(TASKID)
				.ok('Do it!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'tasks/remove/' + TASKID, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'tasks';
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

		$scope.DeleteFile = function (index) {
			var file = $scope.files[index];
			var dataObj = $.param({
				fileid: file.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'tasks/deletefile';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.files.splice($scope.files.indexOf(file), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/tasktimelogs/' + TASKID).then(function (TimeLogs) {
		$scope.timelogs = TimeLogs.data;
		$scope.getTotal = function () {
			var total = 0;
			for (var i = 0; i < $scope.timelogs.length; i++) {
				var timelog = $scope.timelogs[i];
				total += (timelog.timed);
			}
			return total;
		};
		$scope.ProjectTotalAmount = function () {
			var total = 0;
			for (var i = 0; i < $scope.timelogs.length; i++) {
				var timelog = $scope.timelogs[i];
				total += (timelog.amount);
			}
			return total;
		};
	});

	$http.get(BASE_URL + 'api/milestones').then(function (Milestones) {
		$scope.milestones = Milestones.data;
	});

	$http.get(BASE_URL + 'api/taskfiles/' + TASKID).then(function (Files) {
		$scope.files = Files.data;
	});

	$http.get(BASE_URL + 'api/subtasks/' + TASKID).then(function (Subtasks) {
		$scope.subtasks = Subtasks.data;
		$scope.createTask = function () {
			var dataObj = $.param({
				description: $scope.newTitle,
				taskid: TASKID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'tasks/addsubtask';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.subtasks.unshift({
							description: $scope.newTitle,
							date: Date.now()
						});
						$scope.newTitle = '';
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.removeTask = function (index) {
			var subtask = $scope.subtasks[index];
			var dataObj = $.param({
				subtask: subtask.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'tasks/removesubtasks', dataObj, config)
				.then(
					function (response) {
						$scope.subtasks.splice($scope.subtasks.indexOf(subtask), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.completeTask = function (index) {
			var subtask = $scope.subtasks[index];
			var dataObj = $.param({
				subtask: subtask.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'tasks/completesubtasks', dataObj, config)
				.then(
					function (response) {
						subtask.complete = true;
						$scope.subtasks.splice($scope.subtasks.indexOf(subtask), 1);
						$scope.SubTasksComplete.unshift(subtask);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.uncompleteTask = function (index) {
			var task = $scope.SubTasksComplete[index];
			var dataObj = $.param({
				task: task.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'tasks/uncompletesubtasks', dataObj, config)
				.then(
					function (response) {
						var task = $scope.SubTasksComplete[index];
						$scope.SubTasksComplete.splice($scope.SubTasksComplete.indexOf(task), 1);
						$scope.subtasks.unshift(task);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};

	});

	$http.get(BASE_URL + 'api/subtaskscomplete/' + TASKID).then(function (SubTasksComplete) {
		$scope.taskCompletionTotal = function (unit) {
			var total = $scope.taskLength();
			return Math.floor(100 / total * unit);
		};
		$scope.SubTasksComplete = SubTasksComplete.data;
		$scope.taskLength = function () {
			return $scope.subtasks.length + $scope.SubTasksComplete.length;
		};
	});

	$scope.MarkAsCompleteTask = function () {
		var dataObj = $.param({
			task: TASKID,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'tasks/markascompletetask', dataObj, config)
			.then(
				function (response) {
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: 'Task Marked as Complete',
						class_name: 'color success'
					});
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.MarkAsCancelled = function () {
		var dataObj = $.param({
			task: TASKID,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'tasks/markascancelled', dataObj, config)
			.then(
				function (response) {
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: 'Task marked as cancelled',
						class_name: 'color danger'
					});
					console.log(response);
				},
				function (response) {
					console.log(response);
				}
			);
	};
}

function Expenses_Controller($scope, $http, $mdSidenav, $mdDialog, $q, $timeout, $filter) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'expense').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$scope.expensesLoader = true;
		$http.get(BASE_URL + 'api/products').then(function (Products) {
			$scope.products = Products.data;
			$scope.expensesLoader = false;
		});

		$scope.GetProduct = (function (search) {
			var deferred = $q.defer();
			$timeout(function () {
				deferred.resolve($scope.products);
			}, Math.random() * 500, false);
			return deferred.promise;
		});

		$scope.newexpense = {
			items: [{
				name: new_item,
				product_id: 0,
				code: '',
				description: '',
				quantity: 1,
				unit: item_unit,
				price: 0,
				tax: 0,
				discount: 0,
			}]
		};

		$scope.add = function () {
			$scope.newexpense.items.push({
				name: new_item,
				product_id: 0,
				code: '',
				description: '',
				quantity: 1,
				unit: item_unit,
				price: 0,
				tax: 0,
				discount: 0,
			});
		};

		$scope.remove = function (index) {
			$scope.newexpense.items.splice(index, 1);
		};

		$scope.subtotal = function () {
			var subtotal = 0;
			angular.forEach($scope.newexpense.items, function (item) {
				subtotal += item.quantity * item.price;
			});
			return subtotal.toFixed(2);
		};

		$scope.linediscount = function () {
			var linediscount = 0;
			angular.forEach($scope.newexpense.items, function (item) {
				linediscount += ((item.discount) / 100 * item.quantity * item.price);
			});
			return linediscount.toFixed(2);
		};

		$scope.totaltax = function () {
			var totaltax = 0;
			angular.forEach($scope.newexpense.items, function (item) {
				totaltax += ((item.tax) / 100 * item.quantity * item.price);
			});
			return totaltax.toFixed(2);
		};

		$scope.grandtotal = function () {
			var grandtotal = 0;
			angular.forEach($scope.newexpense.items, function (item) {
				grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
			});
			return grandtotal.toFixed(2);
		};

		$scope.today = new Date();

		$http.get(BASE_URL + 'api/customers').then(function (Customers) {
			$scope.all_customers = Customers.data;
		});

		

	$scope.NewExpense = buildToggler('NewExpense');
	$scope.toggleFilter = buildToggler('ContentFilter');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();
		};
	}

	$scope.close = function () {
		$('.md-select-menu-container.md-active.md-clickable').css('display', 'block');
		$mdSidenav('NewExpense').close();
		$mdSidenav('ContentFilter').close();
		$mdSidenav('CreateCustomer').close();
		$mdDialog.hide();
	};

	$scope.AddExpense = function () {
		$scope.savingExpense = true;
		$scope.tempArr = [];
		angular.forEach($scope.custom_fields, function (value) {
			if (value.type === 'input') {
				$scope.field_data = value.data;
			}
			if (value.type === 'textarea') {
				$scope.field_data = value.data;
			}
			if (value.type === 'date') {
				$scope.field_data = moment(value.data).format("YYYY-MM-DD");
			}
			if (value.type === 'select') {
				$scope.field_data = JSON.stringify(value.selected_opt);
			}
			$scope.tempArr.push({
				id: value.id,
				name: value.name,
				type: value.type,
				order: value.order,
				data: $scope.field_data,
				relation: value.relation,
				permission: value.permission,
			});
		});

		var expense_recurring;
		if ($scope.expense_recurring == true) {
			expense_recurring = '1';
		} else {
			expense_recurring = '0';
		}

		var EndRecurring;
		if ($scope.EndRecurring) {
			EndRecurring = moment($scope.EndRecurring).format("YYYY-MM-DD 00:00:00");
		} else {
			EndRecurring = 'Invalid date';
		}

		var internal = false;
		if ($scope.newexpense.internal == true) {
			internal = true;
		}

		if (!$scope.newexpense) {
			var dataObj = $.param({
				title: '',
				amount: '',
				date: '',
				category: '',
				account: '',
				description: '',
				customer: '',
				number: '',
				custom_fields: '',
			});
		} else {
			var dataObj = $.param({
				title: $scope.newexpense.title,
				amount: $scope.newexpense.amount,
				date: moment($scope.newexpense.date).format("YYYY-MM-DD"),
				category: $scope.newexpense.category,
				account: $scope.newexpense.account,
				customer: $scope.newexpense.customer,
				custom_fields: $scope.tempArr,
				number: $scope.newexpense.number,
				internal: internal,
				sub_total: $scope.subtotal,
				total_discount: $scope.linediscount,
				total_tax: $scope.totaltax,
				total: $scope.grandtotal,
				staff: $scope.newexpense.staff,
				// START Recurring
				recurring: expense_recurring,
				end_recurring: EndRecurring,
				recurring_type: $scope.recurring_type,
				recurring_period: $scope.recurring_period,
				// END Recurring
				items: $scope.newexpense.items,
				totalItems: $scope.newexpense.items.length
			});
		}
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'expenses/create/';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					var types = response.data.type;
					$scope.savingExpense = false;
					if (response.data.success == true) {
						//showToast(NTFTITLE, response.data.message, ' success');
						$mdSidenav('NewExpense').close();
						window.location.href = BASE_URL + 'expenses/receipt/' + response.data.id;
					} else {
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.savingExpense = false;
					console.log(response);
				}
			);
	};

		$http.get(BASE_URL + 'api/expenses').then(function (Expenses) {
			$scope.expenses = Expenses.data;
			$scope.expensesLoader = false;

			$scope.search = {
				title: '',
			};
			// Filtered Datas
			$scope.filter = {};
			$scope.getOptionsFor = function (propName) {
				return ($scope.expenses || []).map(function (item) {
					return item[propName];
				}).filter(function (item, idx, arr) {
					return arr.indexOf(item) === idx;
				}).sort();
			};
			$scope.FilteredData = function (item) {
				// Use this snippet for matching with AND
				var matchesAND = true;
				for (var prop in $scope.filter) {
					if (noSubFilter($scope.filter[prop])) {
						continue;
					}
					if (!$scope.filter[prop][item[prop]]) {
						matchesAND = false;
						break;
					}
				}
				return matchesAND;
			};

			function noSubFilter(subFilterObj) {
				for (var key in subFilterObj) {
					if (subFilterObj[key]) {
						return false;
					}
				}
				return true;
			}
			// Filtered Datas
			$scope.itemsPerPage = 5;
			$scope.currentPage = 0;
			$scope.range = function () {
				var rangeSize = 5;
				var ps = [];
				var start;

				start = $scope.currentPage;
				//  console.log($scope.pageCount(),$scope.currentPage)
				if (start > $scope.pageCount() - rangeSize) {
					start = $scope.pageCount() - rangeSize + 1;
				}

				for (var i = start; i < start + rangeSize; i++) {
					if (i >= 0) {
						ps.push(i);
					}
				}
				return ps;
			};

			$scope.prevPage = function () {
				if ($scope.currentPage > 0) {
					$scope.currentPage--;
				}
			};

			$scope.DisablePrevPage = function () {
				return $scope.currentPage === 0 ? "disabled" : "";
			};

			$scope.nextPage = function () {
				if ($scope.currentPage < $scope.pageCount()) {
					$scope.currentPage++;
				}
			};

			$scope.DisableNextPage = function () {
				return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
			};

			$scope.setPage = function (n) {
				$scope.currentPage = n;
			};

			$scope.pageCount = function () {
				return Math.ceil($scope.expenses.length / $scope.itemsPerPage) - 1;
			};
		});

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});

	$scope.expensesCatLoader = true;
	$http.get(BASE_URL + 'api/expensescategories').then(function (Categories) {
		$scope.categories = Categories.data;
		$scope.expensesCatLoader = false;

		$scope.NewCategory = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.prompt()
				.title($scope.lang.newcategory)
				.textContent($scope.lang.type_categoryname)
				.placeholder($scope.lang.categoryname)
				.ariaLabel($scope.lang.categoryname)
				.initialValue('')
				.required(true)
				.ok($scope.lang.add)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'expenses/add_category/', dataObj, config)
					.then(
						function (response) {
							showToast(NTFTITLE, response.data.message, ' success');
							$http.get(BASE_URL + 'api/expensescategories').then(function (Categories) {
								$scope.categories = Categories.data;
							});
						},
						function (response) {
							console.log(response);
						}
					);
			}, function () {

			});
		};

		$scope.UpdateCategory = function (index) {
			var Category = $scope.categories[index];
			var confirm = $mdDialog.prompt()
				.title('Update Category')
				.textContent('Type new category name.')
				.placeholder('Category Name')
				.ariaLabel('Category Name')
				.initialValue(Category.name)
				.targetEvent(event)
				.required(true)
				.ok('Save')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'expenses/update_category/' + Category.id, dataObj, config)
					.then(
						function () {
							Category.name = result;
						},
						function () {
							//UNSUCCESS
						}
					);
			}, function () {
				//Cancel
			});
		};

		$scope.Remove = function (index) {
			var Category = $scope.categories[index];
			var confirm = $mdDialog.confirm()
				.title('Attention!')
				.textContent('Do you confirm the deletion of all data belonging to this expense?')
				.ariaLabel('Delete Project')
				.targetEvent(Category.id)
				.ok('Do it!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'expenses/remove_category/' + Category.id, config)
					.then(
						function (response) {
							console.log(response);
							$scope.categories.splice(index, 1);
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

	});

	$scope.CreateCustomer = function() {
		$mdSidenav('NewExpense').close();
		$('.md-select-menu-container.md-active.md-clickable').css('display', 'none');
		$mdSidenav('CreateCustomer').toggle();
	}

	$scope.isIndividual = false;
		$scope.saving = false;
		$scope.AddCustomer = function () {
			$scope.saving = true;
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
			});
			var dataObj = $.param({
				company: $scope.customer.company,
				namesurname: $scope.customer.namesurname,
				taxoffice: $scope.customer.taxoffice,
				taxnumber: $scope.customer.taxnumber,
				ssn: $scope.customer.ssn,
				executive: $scope.customer.executive,
				address: $scope.customer.address,
				zipcode: $scope.customer.zipcode,
				country_id: $scope.customer.country_id,
				state: $scope.customer.state,
				city: $scope.customer.city,
				town: $scope.customer.town,
				phone: $scope.customer.phone,
				fax: $scope.customer.fax,
				email: $scope.customer.email,
				web: $scope.customer.web,
				risk: $scope.customer.risk,
				billing_street: $scope.customer.billing_street,
				billing_city: $scope.customer.billing_city,
				billing_state: $scope.customer.billing_state,
				billing_zip: $scope.customer.billing_zip,
				billing_country: $scope.customer.billing_country,
				shipping_street: $scope.customer.shipping_street,
				shipping_city: $scope.customer.shipping_city,
				shipping_state: $scope.customer.shipping_state,
				shipping_zip: $scope.customer.shipping_zip,
				shipping_country: $scope.customer.shipping_country,
				type: $scope.isIndividual,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'customers/create/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.saving = false;
						$http.get(BASE_URL + 'api/customers').then(function (Customers) {
							$scope.all_customers = Customers.data;
						});
						showToast(NTFTITLE, $scope.lang.customer+ ' ' +$scope.lang.createmessage, ' success');
						$mdSidenav('CreateCustomer').close();
						$('.md-select-menu-container.md-active.md-clickable').css('display', 'block');
						$mdSidenav('NewExpense').toggle();
					},
					function (response) {
						console.log(response);
						$('#newcustomer').css('display', 'block');
						$scope.saving = false;
					}
				);
		};

		$scope.SameAsCustomerAddress = function () {
			$scope.customer.billing_street = $scope.customer.address;
			$scope.customer.billing_city = $scope.customer.city;
			$scope.customer.billing_state = $scope.customer.state;
			$scope.customer.billing_zip = $scope.customer.zipcode;
			$scope.customer.billing_country = $scope.customer.country_id;
		};

		$scope.SameAsBillingAddress = function () {
			$scope.customer.shipping_street = $scope.customer.billing_street;
			$scope.customer.shipping_city = $scope.customer.billing_city;
			$scope.customer.shipping_state = $scope.customer.billing_state;
			$scope.customer.shipping_zip = $scope.customer.billing_zip;
			$scope.customer.shipping_country = $scope.customer.billing_country;
		};
}

function Expense_Controller($scope, $http, $mdSidenav, $mdDialog, $q, $timeout) {
	"use strict";

	$scope.Update = buildToggler('Update');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();
		};
	}

	$scope.close = function () {
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'expense/' + EXPENSEID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$scope.expensesLoader = true;
	$http.get(BASE_URL + 'api/expense/' + EXPENSEID).then(function (Expense) {
		$scope.expense = Expense.data;
		$scope.expensesLoader = false;

		$scope.subtotal = function () {
			var subtotal = 0;
			angular.forEach($scope.expense.items, function (item) {
				subtotal += item.quantity * item.price;
			});
			return subtotal.toFixed(2);
		};
		$scope.linediscount = function () {
			var linediscount = 0;
			angular.forEach($scope.expense.items, function (item) {
				linediscount += ((item.discount) / 100 * item.quantity * item.price);
			});
			return linediscount.toFixed(2);
		};
		$scope.totaltax = function () {
			var totaltax = 0;
			angular.forEach($scope.expense.items, function (item) {
				totaltax += ((item.tax) / 100 * item.quantity * item.price);
			});
			return totaltax.toFixed(2);
		};
		$scope.grandtotal = function () {
			var grandtotal = 0;
			angular.forEach($scope.expense.items, function (item) {
				grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
			});
			return grandtotal.toFixed(2);
		};

		$http.get(BASE_URL + 'api/products').then(function (Products) {
			$scope.products = Products.data;
		});

		$scope.GetProduct = (function (search) {
			console.log(search);
			var deferred = $q.defer();
			$timeout(function () {
				deferred.resolve($scope.products);
			}, Math.random() * 500, false);
			return deferred.promise;
		});

		$scope.add = function () {
			$scope.expense.items.push({
				name: new_item,
				product_id: 0,
				code: '',
				description: '',
				quantity: 1,
				unit: item_unit,
				price: 0,
				tax: 0,
				discount: 0,
			});
		};

		$scope.remove = function (index) {
			var item = $scope.expense.items[index];
			$http.post(BASE_URL + 'invoices/remove_item/' + item.id)
				.then(
					function (response) {
						console.log(response);
						$scope.expense.items.splice(index, 1);
						$scope.expense.balance = $scope.expense.balance - item.total;
						$scope.amount = $scope.expense.balance;
					},
					function (response) {
						console.log(response);
					}
				);
		};


		$scope.UpdateExpense = function () {
			$scope.savingExpense = true;
				$scope.tempArr = [];
				angular.forEach($scope.custom_fields, function (value) {
					if (value.type === 'input') {
						$scope.field_data = value.data;
					}
					if (value.type === 'textarea') {
						$scope.field_data = value.data;
					}
					if (value.type === 'date') {
						$scope.field_data = moment(value.data).format("YYYY-MM-DD");
					}
					if (value.type === 'select') {
						$scope.field_data = JSON.stringify(value.selected_opt);
					}
					$scope.tempArr.push({
						id: value.id,
						name: value.name,
						type: value.type,
						order: value.order,
						data: $scope.field_data,
						relation: value.relation,
						permission: value.permission,
					});
				});
				var expense_recurring;
				if ($scope.expense_recurring == true) { 
					expense_recurring = '1';
				} else {
					expense_recurring = '0';
				}

				var EndRecurring;
				if ($scope.expense.EndRecurring) {
					EndRecurring = moment($scope.expense.EndRecurring).format("YYYY-MM-DD 00:00:00");
				} else {
					EndRecurring = 'Invalid date';
				}

				var internal = false;
				if ($scope.expense.internal == true) {
					internal = true;
				}
				if ($scope.expense.customer == '0') {
					$scope.expense.customer = '';
				}

				if (!$scope.expense) {
					var dataObj = $.param({
						title: '',
						amount: '',
						date: '',
						category: '',
						account: '',
						description: '',
						customer: '',
						number: '',
						custom_fields: '',
					});
				} else {
					var dataObj = $.param({
						title: $scope.expense.title,
						amount: $scope.expense.amount,
						date: moment($scope.expense.date).format("YYYY-MM-DD"),
						category: $scope.expense.category,
						account: $scope.expense.account,
						customer: $scope.expense.customer,
						custom_fields: $scope.tempArr,
						number: $scope.expense.number,
						internal: internal,
						sub_total: $scope.subtotal,
						total_discount: $scope.linediscount,
						total_tax: $scope.totaltax,
						total: $scope.grandtotal,
						staff: $scope.expense.staff_id,
						// START Recurring
						recurring_status: expense_recurring,
						recurring: $scope.expense.recurring_status,
						end_recurring: EndRecurring,
						recurring_type: $scope.expense.recurring_type,
						recurring_period: $scope.expense.recurring_period,
						recurring_id: $scope.expense.recurring_id,
						// END Recurring
						items: $scope.expense.items,
						totalItems: $scope.expense.items.length
					});
				}
					
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				var posturl = BASE_URL + 'expenses/update/'+EXPENSEID;
				$http.post(posturl, dataObj, config)
					.then(
						function (response) {
							$scope.savingExpense = false;
							if (response.data.success == true) {
								//showToast(NTFTITLE, response.data.message, ' success');
								window.location.href = BASE_URL + 'expenses/receipt/' + response.data.id;
							} else {
								showToast(NTFTITLE, response.data.message, ' danger');
							}
						},
						function (response) {
							$scope.savingExpense = false;
							console.log(response);
						}
					);
		};

		$scope.sendEmail = function() {
			$scope.sendingEmail = true;
			$http.post(BASE_URL + 'expenses/send_expense_email/' + EXPENSEID)
				.then(
					function (response) {
						showToast(NTFTITLE, lang.email_sent_success, 'success');
						$scope.sendingEmail = false;
					},
					function (response) {
						$scope.sendingEmail = false;
						showToast(NTFTITLE, response, 'success');
						console.log(response);
					}
				);
		}

		$scope.GeneratePDF = function(ev) {
			$mdDialog.show({
				templateUrl: 'generate-expense-summary.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: ev
			});
		}

		$scope.CreatePDF = function() {
			$scope.PDFCreating = true;
			$http.post(BASE_URL + 'expenses/create_pdf/' + EXPENSEID)
				.then(
					function (response) {
						console.log(response)
						if (response.data.status === true) {
							$scope.PDFCreating = false;
							$scope.CreatedPDFName = response.data.file_name;
						}
					},
					function (response) {
						console.log(response);
					}
				);
		}
		
		$http.get(BASE_URL + 'api/expensescategories').then(function (Categories) {
			$scope.categories = Categories.data;
		});

		$scope.Delete = function () {
			var confirm = $mdDialog.confirm()
				.title('Attention!')
				.textContent('Do you confirm the deletion of all data belonging to this expense?')
				.ariaLabel('Delete Project')
				.targetEvent(EXPENSEID)
				.ok('Do it!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'expenses/remove/' + EXPENSEID, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'expenses';
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};
		$scope.Convert = function () {
			var confirm = $mdDialog.confirm()
				.title('Information!')
				.textContent('Do you want to convert this expense to invoice?')
				.ariaLabel('Convert Expense to Invoice')
				.targetEvent(EXPENSEID)
				.ok('Convert!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'expenses/convert/' + EXPENSEID, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'invoices/invoice/' + response.data;
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};
	});

	$scope.UploadFile = function (ev) {
		$mdDialog.show({
			templateUrl: 'addfile-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

		

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});

	$scope.expensesFiles = true;
	$http.get(BASE_URL + 'expenses/files/'+ EXPENSEID).then(function (Files) {
		$scope.files = Files.data;

		$scope.itemsPerPage = 6;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 6;
			var ps = [];
			var start;

			start = $scope.currentPage;
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}
			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};
		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};
		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};
		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};
		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};
		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};
		$scope.pageCount = function () {
			return Math.ceil($scope.files.length / $scope.itemsPerPage) - 1;
		};
		$scope.expensesFiles = false;
		$scope.ViewFile = function(index, image) {
			$scope.file = $scope.files[index];
			$mdDialog.show({
				templateUrl: 'view_image.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: $scope.file.id
			});
		}

		$scope.DeleteFile = function(index) {
			$scope.file = $scope.files[index];
			var confirm = $mdDialog.confirm()
				.title($scope.lang.delete_file_title)
				.textContent($scope.lang.delete_file_message)
				.ariaLabel($scope.lang.delete_file_title)
				.targetEvent(EXPENSEID)
				.ok($scope.lang.delete)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'expenses/delete_file/' + $scope.file.id, config)
					.then(
						function (response) {
							if(response.data.success == true) {
								showToast(NTFTITLE, response.data.message, ' success');
								$http.get(BASE_URL + 'expenses/files/'+ EXPENSEID).then(function (Files) {
									$scope.files = Files.data;
								});
							} else {
								showToast(NTFTITLE, response.data.message, ' danger');
							}
						},
						function (response) {
							console.log(response);
						}
					);

			}, function() {
				//
			});
		};
		$scope.DeleteFiles = function(id) {
			var confirm = $mdDialog.confirm()
				.title($scope.lang.delete_file_title)
				.textContent($scope.lang.delete_file_message)
				.ariaLabel($scope.lang.delete_file_title)
				.targetEvent(EXPENSEID)
				.ok($scope.lang.delete)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'expenses/delete_file/' + id, config)
					.then(
						function (response) {
							if(response.data.success == true) {
								showToast(NTFTITLE, response.data.message, ' success');
								$http.get(BASE_URL + 'expenses/files/'+ EXPENSEID).then(function (Files) {
									$scope.files = Files.data;
								});
							} else {
								showToast(NTFTITLE, response.data.message, ' danger');
							}
						},
						function (response) {
							console.log(response);
						}
					);

			}, function() {
				//
			});
		};
	});

	$http.get(BASE_URL + 'api/expensescategories').then(function (Epxensescategories) {
		$scope.expensescategories = Epxensescategories.data;
	});

}

function Invoices_Controller($scope, $http, $mdSidenav, $q, $timeout, $filter) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'invoice').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$http.get(BASE_URL + 'api/products').then(function (Products) {
		$scope.products = Products.data;
	});

	$scope.GetProduct = (function (search) {
		console.log(search);
		var deferred = $q.defer();
		$timeout(function () {
			deferred.resolve($scope.products);
		}, Math.random() * 500, false);
		return deferred.promise;
	});

	$scope.invoice = {
		items: [{
			name: new_item,
			product_id: 0,
			code: '',
			description: '',
			quantity: 1,
			unit: item_unit,
			price: 0,
			tax: 0,
			discount: 0,
		}]
	};

	$scope.add = function () {
		$scope.invoice.items.push({ 
			name: new_item,
			product_id: 0,
			code: '',
			description: '',
			quantity: 1,
			unit: item_unit,
			price: 0,
			tax: 0,
			discount: 0,
		});
	};

	$scope.remove = function (index) {
		$scope.invoice.items.splice(index, 1);
	};

	$scope.subtotal = function () {
		var subtotal = 0;
		angular.forEach($scope.invoice.items, function (item) {
			subtotal += item.quantity * item.price;
		});
		return subtotal.toFixed(2);
	};

	$scope.linediscount = function () {
		var linediscount = 0;
		angular.forEach($scope.invoice.items, function (item) {
			linediscount += ((item.discount) / 100 * item.quantity * item.price);
		});
		return linediscount.toFixed(2);
	};

	$scope.totaltax = function () {
		var totaltax = 0;
		angular.forEach($scope.invoice.items, function (item) {
			totaltax += ((item.tax) / 100 * item.quantity * item.price);
		});
		return totaltax.toFixed(2);
	};

	$scope.grandtotal = function () {
		var grandtotal = 0;
		angular.forEach($scope.invoice.items, function (item) {
			grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
		});
		return grandtotal.toFixed(2);
	};

	$scope.today = new Date();

	$scope.saveAll = function () {
		$scope.savingInvoice = true;
		if ($scope.invoice.shipping_country) {
			$scope.shipping_country = $scope.invoice.shipping_country.id;
		} else {
			$scope.shipping_country = null;
		}
		if ($scope.invoice.billing_country) {
			$scope.billing_country = $scope.invoice.billing_country.id;
		} else {
			$scope.billing_country = null;
		}
		$scope.tempArr = [];
		angular.forEach($scope.custom_fields, function (value) {
			if (value.type === 'input') {
				$scope.field_data = value.data;
			}
			if (value.type === 'textarea') {
				$scope.field_data = value.data;
			}
			if (value.type === 'date') {
				$scope.field_data = moment(value.data).format("YYYY-MM-DD");
			}
			if (value.type === 'select') {
				$scope.field_data = JSON.stringify(value.selected_opt);
			}
			$scope.tempArr.push({
				id: value.id,
				name: value.name,
				type: value.type,
				order: value.order,
				data: $scope.field_data,
				relation: value.relation,
				permission: value.permission,
			});
		});

		var invoice_recurring;
		if ($scope.invoice_recurring == true) {
			invoice_recurring = '1';
		} else {
			invoice_recurring = '0';
		}

		var EndRecurring;
		if ($scope.EndRecurring) {
			EndRecurring = moment($scope.EndRecurring).format("YYYY-MM-DD 00:00:00");
		} else {
			EndRecurring = 'Invalid date';
		}

		if ($scope.created) {
			$scope.created = moment($scope.created).format("YYYY-MM-DD");
		}
		if ($scope.duedate) {
			$scope.duedate = moment($scope.duedate).format("YYYY-MM-DD");
		}
		if ($scope.datepayment) {
			$scope.datepayment = moment($scope.datepayment).format("YYYY-MM-DD");
		}
		var dataObj = $.param({
			customer: $scope.customer.id,
			created: $scope.created,
			duedate: $scope.duedate,
			datepayment: $scope.datepayment,
			account: $scope.account,
			duenote: $scope.duenote,
			serie: $scope.serie,
			no: $scope.no,
			sub_total: $scope.subtotal,
			total_discount: $scope.linediscount,
			total_tax: $scope.totaltax,
			total: $scope.grandtotal,
			status: $scope.invoice_status,
			// Billing Address
			billing_street: $scope.invoice.billing_street,
			billing_city: $scope.invoice.billing_city,
			billing_state: $scope.invoice.billing_state,
			billing_zip: $scope.invoice.billing_zip,
			billing_country: $scope.billing_country,
			// Shipping Address
			shipping_street: $scope.invoice.shipping_street,
			shipping_city: $scope.invoice.shipping_city,
			shipping_state: $scope.invoice.shipping_state,
			shipping_zip: $scope.invoice.shipping_zip,
			shipping_country: $scope.shipping_country,
			// START Recurring
			recurring: invoice_recurring,
			end_recurring: EndRecurring,
			recurring_type: $scope.recurring_type,
			recurring_period: $scope.recurring_period,
			// END Recurring
			items: $scope.invoice.items,
			totalItems: $scope.invoice.items.length,
			custom_fields: $scope.tempArr,
			default_payment_method: $scope.default_payment_method
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'invoices/create';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					if (response.data.success == true) {
						window.location.href = BASE_URL + 'invoices/invoice/' + response.data.id;
					} else {
						$scope.savingInvoice = false;
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.savingInvoice = false;
				}
			);
	};

	$scope.CopyBillingFromCustomer = function () {
		$scope.invoice.billing_street = $scope.customer.billing_street;
		$scope.invoice.billing_city = $scope.customer.billing_city;
		$scope.invoice.billing_state = $scope.customer.billing_state;
		$scope.invoice.billing_zip = $scope.customer.billing_zip;
		$scope.invoice.billing_country = $scope.customer.billing_country;
	};

	$scope.CopyShippingFromCustomer = function () {
		$scope.invoice.shipping_street = $scope.customer.shipping_street;
		$scope.invoice.shipping_city = $scope.customer.shipping_city;
		$scope.invoice.shipping_state = $scope.customer.shipping_state;
		$scope.invoice.shipping_zip = $scope.customer.shipping_zip;
		$scope.invoice.shipping_country = $scope.customer.shipping_country;
	};

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});

	$scope.SelectedCustomer = $scope.customer;

	$scope.invoiceLoader = true;
	$http.get(BASE_URL + 'api/invoices').then(function (Invoices) {
		$scope.invoices = Invoices.data;
		$scope.invoiceLoader = false;
		$scope.search = {
			customer: ''
		};
		// Filter Buttons //
		$scope.toggleFilter = buildToggler('ContentFilter');

		function buildToggler(navID) {
			return function () {
				$mdSidenav(navID).toggle();

			};
		}
		$scope.close = function () {
			$mdSidenav('ContentFilter').close();
		};
		// Filter Buttons //
		// Filtered Datas
		$scope.filter = {};
		$scope.getOptionsFor = function (propName) {
			return ($scope.invoices || []).map(function (item) {
				return item[propName];
			}).filter(function (item, idx, arr) {
				return arr.indexOf(item) === idx;
			}).sort();
		};
		$scope.FilteredData = function (item) {
			// Use this snippet for matching with AND
			var matchesAND = true;
			for (var prop in $scope.filter) {
				if (noSubFilter($scope.filter[prop])) {
					continue;
				}
				if (!$scope.filter[prop][item[prop]]) {
					matchesAND = false;
					break;
				}
			}
			return matchesAND;

		};

		function noSubFilter(subFilterObj) {
			for (var key in subFilterObj) {
				if (subFilterObj[key]) {
					return false;
				}
			}
			return true;
		}
		$scope.updateDropdown = function (_prop) {
				var _opt = this.filter_select,
					_optList = this.getOptionsFor(_prop),
					len = _optList.length;

				if (_opt == 'all') {
					for (var j = 0; j < len; j++) {
						$scope.filter[_prop][_optList[j]] = true;
					}
				} else {
					for (var j = 0; j < len; j++) {
						$scope.filter[_prop][_optList[j]] = false;
					}
					$scope.filter[_prop][_opt] = true;
				}
			}
			// Filtered Datas
		$scope.itemsPerPage = 5;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 5;
			var ps = [];
			var start;

			start = $scope.currentPage;
			//  console.log($scope.pageCount(),$scope.currentPage)
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};

		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};

		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};

		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};

		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};

		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};

		$scope.pageCount = function () {
			return Math.ceil($scope.invoices.length / $scope.itemsPerPage) - 1;
		};
	});
}

function Invoice_Controller($scope, $http, $mdSidenav, $mdDialog, $q, $timeout, $filter) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'invoice/' + INVOICEID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$scope.sendEmail = function() {
		$scope.sendingEmail = true;
		$http.post(BASE_URL + 'invoices/send_invoice_email/' + INVOICEID)
			.then(
				function (response) {
					console.log(response)
					if (response.data.status === true) {
						showToast(NTFTITLE, response.data.message, 'success');
					}
					$scope.sendingEmail = false;
				},
				function (response) {
					console.log(response);
				}
			);
	}

	$scope.GeneratePDF = function (ev) {
		$mdDialog.show({
			templateUrl: 'generate-invoice.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.CreatePDF = function () {
		$scope.PDFCreating = true;
		$http.post(BASE_URL + 'invoices/create_pdf/' + INVOICEID)
			.then(
				function (response) {
					console.log(response);
					if (response.data.status === true) {
						$scope.PDFCreating = false;
						$scope.CreatedPDFName = response.data.file_name;
					}
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.Delete = function () {
		// Appending dialog to document.body to cover sidenav in docs app
		var confirm = $mdDialog.confirm()
			.title($scope.lang.deleteinvoice)
			.textContent($scope.lang.inv_remove_msg)
			.ariaLabel('Delete Invoice')
			.targetEvent(INVOICEID)
			.ok($scope.lang.delete)
			.cancel($scope.lang.cancel);

		$mdDialog.show(confirm).then(function () {
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'invoices/remove/' + INVOICEID, config)
				.then(
					function (response) {
						console.log(response);
						window.location.href = BASE_URL + 'invoices';
					},
					function (response) {
						console.log(response);
					}
				);

		}, function () {
			//
		});
	};

	$scope.invoiceLoader = true;
	$http.get(BASE_URL + 'api/invoice/' + INVOICEID).then(function (InvoiceDetails) {
		$scope.invoice = InvoiceDetails.data; 
		$http.get(BASE_URL + 'api/customers/').then(function (Data) {
			$scope.customers = Data.data; 
		});
		$http.get(BASE_URL + 'api/contacts').then(function (Contacts) {
			$scope.all_contacts = Contacts.data;
			$scope.contacts = $filter('filter')($scope.all_contacts, {
				customer_id: $scope.invoice.customer,
			});
		});
		$scope.invoiceLoader = false;
		$scope.MarkAsDraft = function () {
			$http.post(BASE_URL + 'invoices/mark_as_draft/' + INVOICEID)
				.then(
					function (response) {
						console.log(response);
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: response.data,
							class_name: 'color success'
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.MarkAsCancelled = function () {
			$http.post(BASE_URL + 'invoices/mark_as_cancelled/' + INVOICEID)
				.then(
					function (response) {
						console.log(response);
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: response.data,
							class_name: 'color danger'
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.subtotal = function () {
			var subtotal = 0;
			angular.forEach($scope.invoice.items, function (item) {
				subtotal += item.quantity * item.price;
			});
			return subtotal.toFixed(2);
		};
		$scope.linediscount = function () {
			var linediscount = 0;
			angular.forEach($scope.invoice.items, function (item) {
				linediscount += ((item.discount) / 100 * item.quantity * item.price);
			});
			return linediscount.toFixed(2);
		};
		$scope.totaltax = function () {
			var totaltax = 0;
			angular.forEach($scope.invoice.items, function (item) {
				totaltax += ((item.tax) / 100 * item.quantity * item.price);
			});
			return totaltax.toFixed(2);
		};
		$scope.grandtotal = function () {
			var grandtotal = 0;
			angular.forEach($scope.invoice.items, function (item) {
				grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
			});
			return grandtotal.toFixed(2);
		};

		$scope.totalpaid = function () {
			return $scope.invoice.payments.reduce(function (total, payment) {
				return total + (payment.amount * 1 || 0);
			}, 0);
		};

		$scope.amount = $scope.invoice.balance;

		$http.get(BASE_URL + 'api/products').then(function (Products) {
			$scope.products = Products.data;
		});

		$scope.GetProduct = (function (search) {
			console.log(search);
			var deferred = $q.defer();
			$timeout(function () {
				deferred.resolve($scope.products);
			}, Math.random() * 500, false);
			return deferred.promise;
		});

		$scope.add = function () {
			$scope.invoice.items.push({
				name: new_item,
				product_id: 0,
				code: '',
				description: '',
				quantity: 1,
				unit: item_unit,
				price: 0,
				tax: 0,
				discount: 0,
			});
		};

		$scope.remove = function (index) {
			var item = $scope.invoice.items[index];
			$http.post(BASE_URL + 'invoices/remove_item/' + item.id)
				.then(
					function (response) {
						console.log(response);
						$scope.invoice.items.splice(index, 1);
						$scope.invoice.balance = $scope.invoice.balance - item.total;
						$scope.amount = $scope.invoice.balance;
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.changeBank = function(id) {
			var customer = '';
			for (var i = 0; i < $scope.customers.length; i++) {
				if ($scope.customers[i].id == id) {
					customer = $scope.customers[i];
					continue;
				}
			}
			$scope.invoice.default_payment_method = customer.default_payment_method;
		}

		$scope.saveAll = function () {
			$scope.savingInvoice = true;
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			var EndRecurring;
			if ($scope.invoice.recurring_endDate) {
				EndRecurring = moment($scope.invoice.recurring_endDate).format("YYYY-MM-DD 00:00:00");
			} else {
				EndRecurring = 'Invalid date';
			}
			if ($scope.invoice.created) {
				$scope.invoice.created = moment($scope.invoice.created).format("YYYY-MM-DD");
			}
			if ($scope.invoice.duedate) {
				$scope.invoice.duedate = moment($scope.invoice.duedate).format("YYYY-MM-DD");
			}
			var dataObj = $.param({
				customer: $scope.invoice.customer,
				created: $scope.invoice.created,
				duedate: $scope.invoice.duedate,
				duenote: $scope.invoice.duenote,
				serie: $scope.invoice.serie,
				no: $scope.invoice.no,
				sub_total: $scope.subtotal,
				total_discount: $scope.linediscount,
				total_tax: $scope.totaltax,
				total: $scope.grandtotal,
				// START Recurring
				recurring_status: $scope.invoice.recurring_status,
				recurring: $scope.invoice.recurring_status,
				end_recurring: EndRecurring,
				recurring_type: $scope.invoice.recurring_type,
				recurring_period: $scope.invoice.recurring_period,
				recurring_id: $scope.invoice.recurring_id,
				// END Recurring
				items: $scope.invoice.items,
				totalItems: $scope.invoice.items.length,
				custom_fields: $scope.tempArr,
				default_payment_method: $scope.invoice.default_payment_method
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'invoices/update/' + INVOICEID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							window.location.href = BASE_URL + 'invoices/invoice/' + response.data.id;
						} else {
							$scope.savingInvoice = false;
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.savingInvoice = false;
						console.log(response);
					}
				);
		};
	});

	$scope.UodateInvoice = function (id) {
		window.location.href = BASE_URL + 'invoices/update/' + id;
	};

	$scope.RecordPayment = buildToggler('RecordPayment');
	$scope.Discussions = buildToggler('Discussions');
	$scope.NewDiscussion = buildToggler('NewDiscussion');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('RecordPayment').close();
		$mdSidenav('Discussions').close();
		$mdSidenav('NewDiscussion').close();
	};
	$scope.CloseModal = function () {
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'api/discussions/invoice/' + INVOICEID).then(function (Discussions) {
		$scope.discussions = Discussions.data;
		$scope.Discussion_Detail = function (index) {
			var discussion = $scope.discussions[index];
			$scope.discussions_comments = discussion.comments;
			$scope.AddComment = function (index) {
				var discussion = $scope.discussions[index];
				var dataObj = $.param({
					discussion_id: discussion.id,
					content: discussion.newcontent,
					contact_id: discussion.contact_id,
					full_name: LOGGEDINSTAFFNAME,

				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				var posturl = BASE_URL + 'trivia/add_discussion_comment';
				$http.post(posturl, dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$scope.discussions_comments.push({
								'content': discussion.newcontent,
								'full_name': LOGGEDINSTAFFNAME,
								'created': new Date(),
							});
							$('.comment-description').val('');
						},
						function (response) {
							console.log(response);
						}
					);
			};
			$mdDialog.show({
				contentElement: '#Discussion_Detail-' + discussion.id,
				parent: angular.element(document.body),
				targetEvent: index,
				clickOutsideToClose: true
			});
		};
	});

	$scope.ShowCustomer = false;

	$scope.CreateDiscussion = function () {
		var dataObj = $.param({
			relation_type: 'invoice',
			relation: INVOICEID,
			subject: $scope.new_discussion.subject,
			description: $scope.new_discussion.description,
			contact_id: $scope.new_discussion.contact_id,
			show_to_customer: $scope.ShowCustomer,
			staff_id: ACTIVESTAFF,

		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'trivia/create_discussion';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					console.log(response);
					$scope.discussions.push({
						'id': response.data,
						'subject': $scope.new_discussion.subject,
						'contact': $scope.new_discussion.contact_id,
					});
					$mdSidenav('NewDiscussion').close();
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});
	$scope.doing = false;
	$scope.AddPayment = function () {
		$scope.doing = true;
		var dataObj = $.param({
			date: moment($scope.date).format("YYYY-MM-DD HH:mm:ss"),
			balance: $scope.invoice.balance - $scope.amount,
			amount: $scope.amount,
			not: $scope.not,
			account: $scope.account,
			invoicetotal: $scope.grandtotal,
			staff: ACTIVESTAFF,
			customer: INVOICECUSTOMER,
			invoice: INVOICEID,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'invoices/record_payment';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					console.log(response);
					$scope.invoice.payments.push({
						'name': $scope.account,
						'amount': $scope.amount,
					});
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: response.data,
						position: 'bottom',
						class_name: 'color success',
					});
					$mdSidenav('RecordPayment').close();
					$scope.invoice.balance = $scope.invoice.balance - $scope.amount;
					$scope.doing = false;
				},
				function (response) {
					console.log(response);
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: response.data,
						position: 'bottom',
						class_name: 'color warning',
					});
					$scope.doing = false;
				}
			);
	};
}

function Proposals_Controller($scope, $http, $mdSidenav, $q, $timeout, $filter) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'proposal').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$scope.proposalsLoader = true;

	$http.get(BASE_URL + 'api/products').then(function (Products) {
		$scope.products = Products.data;
		$scope.proposalsLoader = false;
	});

	$http.get(BASE_URL + 'api/leads').then(function (Leads) {
		$scope.leads = Leads.data;
		$scope.proposalsLoader = false;
	});


	$scope.GetProduct = (function (search) {
		console.log(search);
		var deferred = $q.defer();
		$timeout(function () {
			deferred.resolve($scope.products);
		}, Math.random() * 500, false);
		return deferred.promise;
	});

	$scope.proposal = {
		items: [{
			name: new_item,
			product_id: 0,
			code: '',
			description: '',
			quantity: 1,
			unit: item_unit,
			price: 0,
			tax: 0,
			discount: 0,
		}]
	};

	$scope.add = function () {
		$scope.proposal.items.push({
			name: new_item,
			product_id: 0,
			code: '',
			description: '',
			quantity: 1,
			unit: item_unit,
			price: 0,
			tax: 0,
			discount: 0,
		});
	};

	$scope.remove = function (index) {
		$scope.proposal.items.splice(index, 1);
	};

	$scope.subtotal = function () {
		var subtotal = 0;
		angular.forEach($scope.proposal.items, function (item) {
			subtotal += item.quantity * item.price;
		});
		return subtotal.toFixed(2);
	};

	$scope.linediscount = function () {
		var linediscount = 0;
		angular.forEach($scope.proposal.items, function (item) {
			linediscount += ((item.discount) / 100 * item.quantity * item.price);
		});
		return linediscount.toFixed(2);
	};

	$scope.totaltax = function () {
		var totaltax = 0;
		angular.forEach($scope.proposal.items, function (item) {
			totaltax += ((item.tax) / 100 * item.quantity * item.price);
		});
		return totaltax.toFixed(2);
	};

	$scope.grandtotal = function () {
		var grandtotal = 0;
		angular.forEach($scope.proposal.items, function (item) {
			grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
		});
		return grandtotal.toFixed(2);
	};

	$scope.savingProposal = false;
	$scope.saveAll = function () {
		$scope.savingProposal = true;
		$scope.tempArr = [];
		angular.forEach($scope.custom_fields, function (value) {
			if (value.type === 'input') {
				$scope.field_data = value.data;
			}
			if (value.type === 'textarea') {
				$scope.field_data = value.data;
			}
			if (value.type === 'date') {
				$scope.field_data = moment(value.data).format("YYYY-MM-DD");
			}
			if (value.type === 'select') {
				$scope.field_data = JSON.stringify(value.selected_opt);
			}
			$scope.tempArr.push({
				id: value.id,
				name: value.name,
				type: value.type,
				order: value.order,
				data: $scope.field_data,
				relation: value.relation,
				permission: value.permission,
			});
		});
		var created = '', date = '', duedate = '';
		if ($scope.created) {
			created = moment($scope.created).format("YYYY-MM-DD");
		}
		if ($scope.opentill) {
			duedate = moment($scope.opentill).format("YYYY-MM-DD");
		}
		var dataObj = $.param({
			customer: $scope.customer,
			lead: $scope.lead,
			comment: $scope.comment,
			subject: $scope.subject,
			content: $scope.content,
			date: created,
			opentill: duedate,
			proposal_type: $scope.proposal_type,
			status: $scope.status,
			assigned: $scope.assigned,
			sub_total: $scope.subtotal,
			total_discount: $scope.linediscount,
			total_tax: $scope.totaltax,
			total: $scope.grandtotal,
			items: $scope.proposal.items,
			total_items: $scope.proposal.items.length,
			custom_fields: $scope.tempArr,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'proposals/create';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$scope.savingProposal = false;
					if (response.data.success == true) {
						window.location.href = BASE_URL + 'proposals/proposal/' + response.data.proposal_id;
					} else {
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.savingProposal = false;
				}
			);
	};


	$http.get(BASE_URL + 'api/proposals').then(function (Proposals) {
		$scope.proposals = Proposals.data;
		$scope.search = {
			subject: '',
		};
		// Filter Buttons //
		$scope.toggleFilter = buildToggler('ContentFilter');

		function buildToggler(navID) {
			return function () {
				$mdSidenav(navID).toggle();

			};
		}
		$scope.close = function () {
			$mdSidenav('ContentFilter').close();
		};
		// Filter Buttons //
		// Filtered Datas
		$scope.filter = {};
		$scope.getOptionsFor = function (propName) {
			return ($scope.proposals || []).map(function (item) {
				return item[propName];
			}).filter(function (item, idx, arr) {
				return arr.indexOf(item) === idx;
			}).sort();
		};
		$scope.FilteredData = function (item) {
			// Use this snippet for matching with AND
			var matchesAND = true;
			for (var prop in $scope.filter) {
				if (noSubFilter($scope.filter[prop])) {
					continue;
				}
				if (!$scope.filter[prop][item[prop]]) {
					matchesAND = false;
					break;
				}
			}
			return matchesAND;

		};

		function noSubFilter(subFilterObj) {
			for (var key in subFilterObj) {
				if (subFilterObj[key]) {
					return false;
				}
			}
			return true;
		}
		$scope.updateDropdown = function (_prop) {
				var _opt = this.filter_select,
					_optList = this.getOptionsFor(_prop),
					len = _optList.length;

				if (_opt == 'all') {
					for (var j = 0; j < len; j++) {
						$scope.filter[_prop][_optList[j]] = true;
					}
				} else {
					for (var j = 0; j < len; j++) {
						$scope.filter[_prop][_optList[j]] = false;
					}
					$scope.filter[_prop][_opt] = true;
				}
			}
			// Filtered Datas
		$scope.itemsPerPage = 5;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 5;
			var ps = [];
			var start;

			start = $scope.currentPage;
			//  console.log($scope.pageCount(),$scope.currentPage)
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};

		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};

		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};

		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};

		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};

		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};

		$scope.pageCount = function () {
			return Math.ceil($scope.proposals.length / $scope.itemsPerPage) - 1;
		};
	});
}

function Proposal_Controller($scope, $http, $mdSidenav, $mdDialog, $q, $timeout) {
	"use strict";

	$scope.GeneratePDF = function (ev) {
		$mdDialog.show({
			templateUrl: 'generate-proposal.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.sendEmail = function() {
		$scope.sendingEmail = true;
		$http.post(BASE_URL + 'proposals/send_proposal_email/' + PROPOSALID)
			.then(
				function (response) {
					console.log(response)
					if (response.data.status === true) {
						showToast(NTFTITLE, response.data.message, 'success');
					}
					$scope.sendingEmail = false;
				},
				function (response) {
					console.log(response);
				}
			);
	}

	$scope.CreatePDF = function () {
		$scope.PDFCreating = true;
		$http.post(BASE_URL + 'proposals/create_pdf/' + PROPOSALID)
			.then(
				function (response) {
					console.log(response);
					if (response.data.status === true) {
						$scope.PDFCreating = false;
						$scope.CreatedPDFName = response.data.file_name;
					}
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'proposal/' + PROPOSALID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$http.get(BASE_URL + 'api/proposal/' + PROPOSALID).then(function (ProposalDetails) {
		$scope.proposal = ProposalDetails.data;


		$scope.Convert = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title('Information!')
				.textContent('Do you want to convert this proposal to invoice?')
				.ariaLabel('Convert')
				.targetEvent(PROPOSALID)
				.ok('Convert!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var dataObj = $.param({
					total: $scope.ProjectTotalAmount,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'proposals/convert_invoice/' + PROPOSALID, dataObj, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'invoices/update/' + response.data;
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

		$scope.Update = function () {
			window.location.href = BASE_URL + 'proposals/update/' + PROPOSALID;
		};

		$scope.ViewProposal = function () {
			window.location.href = BASE_URL + 'share/proposal/' + $scope.proposal.token;
		};

		$scope.Delete = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title($scope.lang.delete_proposal)
				.textContent($scope.lang.proposal_remove_msg)
				.ariaLabel('Delete Proposal')
				.targetEvent(PROPOSALID)
				.ok($scope.lang.delete)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'proposals/remove/' + PROPOSALID, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'proposals';
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

		$scope.MarkAs = function (id, name) {
			var dataObj = $.param({
				status_id: id,
				proposal_id: PROPOSALID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'proposals/markas/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: '<b>Proposal marked as' + ' ' + name + '</b>',
							class_name: 'color success'
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.subtotal = function () {
			var subtotal = 0;
			angular.forEach($scope.proposal.items, function (item) {
				subtotal += item.quantity * item.price;
			});
			return subtotal.toFixed(2);
		};
		$scope.linediscount = function () {
			var linediscount = 0;
			angular.forEach($scope.proposal.items, function (item) {
				linediscount += ((item.discount) / 100 * item.quantity * item.price);
			});
			return linediscount.toFixed(2);
		};
		$scope.totaltax = function () {
			var totaltax = 0;
			angular.forEach($scope.proposal.items, function (item) {
				totaltax += ((item.tax) / 100 * item.quantity * item.price);
			});
			return totaltax.toFixed(2);
		};
		$scope.grandtotal = function () {
			var grandtotal = 0;
			angular.forEach($scope.proposal.items, function (item) {
				grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
			});
			return grandtotal.toFixed(2);
		};

		$http.get(BASE_URL + 'api/products').then(function (Products) {
			$scope.products = Products.data;
		});

		$http.get(BASE_URL + 'api/leads').then(function (Leads) {
			$scope.leads = Leads.data;
		});

		$scope.GetProduct = (function (search) {
			console.log(search);
			var deferred = $q.defer();
			$timeout(function () {
				deferred.resolve($scope.products);
			}, Math.random() * 500, false);
			return deferred.promise;
		});

		$scope.add = function () {
			$scope.proposal.items.push({
				name: new_item,
				product_id: 0,
				code: '',
				description: '',
				quantity: 1,
				unit: item_unit,
				price: 0,
				tax: 0,
				discount: 0,
			});
		};
		$scope.remove = function (index) {
			var item = $scope.proposal.items[index];
			$http.post(BASE_URL + 'proposals/remove_item/' + item.id)
				.then(
					function (response) {
						console.log(response);
						$scope.proposal.items.splice(index, 1);
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.ProType = $scope.proposal.proposal_type;
		$scope.savingProposal = false;
		$scope.saveAll = function () {
			$scope.savingProposal = true;
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			var created = '', date = '', duedate = '';
			if ($scope.proposal.created) {
				created = moment($scope.proposal.created).format("YYYY-MM-DD");
			}
			if ($scope.proposal.opentill) {
				duedate = moment($scope.proposal.opentill).format("YYYY-MM-DD");
			}
			var dataObj = $.param({
				customer: $scope.proposal.customer,
				lead: $scope.proposal.lead,
				comment: $scope.proposal.comment,
				subject: $scope.proposal.subject,
				content: $scope.proposal.content,
				date: created,
				opentill: duedate,
				proposal_type: $scope.proposal.proposal_type,
				status: $scope.proposal.status,
				assigned: $scope.proposal.assigned,
				sub_total: $scope.subtotal,
				total_discount: $scope.linediscount,
				total_tax: $scope.totaltax,
				total: $scope.grandtotal,
				items: $scope.proposal.items,
				custom_fields: $scope.tempArr,
				total_items: $scope.proposal.items.length,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'proposals/update/' + PROPOSALID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.savingProposal = false;
						if (response.data.success == true) {
							window.location.href = BASE_URL + 'proposals/proposal/' + response.data.id;
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}						
					},
					function (response) {
						$scope.savingProposal = false;
					}
				);
		};
	});

	$scope.ReminderForm = buildToggler('ReminderForm');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}
	$scope.close = function () {
		$mdSidenav('ReminderForm').close();
	};

	$scope.CloseModal = function () {
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'api/products').then(function (Products) {
		$scope.products = Products.data;
	});

	$http.get(BASE_URL + 'api/reminders_by_type/proposal/' + PROPOSALID).then(function (Reminders) {
		$scope.in_reminders = Reminders.data;
		$scope.AddReminder = function () {
			var dataObj = $.param({
				description: $scope.reminder_description,
				date: moment($scope.reminder_date).format("YYYY-MM-DD HH:mm:ss"),
				staff: $scope.reminder_staff,
				relation_type: 'proposal',
				relation: PROPOSALID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/addreminder';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$scope.in_reminders.push({
							'description': $scope.reminder_description,
							'creator': LOGGEDINSTAFFNAME,
							'avatar': UPIMGURL + LOGGEDINSTAFFAVATAR,
							'staff': LOGGEDINSTAFFNAME,
							'date': $scope.reminder_date,
						});
						$mdSidenav('ReminderForm').close();
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.DeleteReminder = function (index) {
			var reminder = $scope.in_reminders[index];
			var dataObj = $.param({
				reminder: reminder.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/removereminder';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.in_reminders.splice($scope.in_reminders.indexOf(reminder), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/notes/proposal/' + PROPOSALID).then(function (Notes) {
		$scope.notes = Notes.data;
		$scope.AddNote = function () {
			var dataObj = $.param({
				description: $scope.note,
				relation_type: 'proposal',
				relation: PROPOSALID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/addnote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$('.note-description').val('');
							$scope.note = '';
							$http.get(BASE_URL + 'api/notes/proposal/' + PROPOSALID).then(function (Notes) {
								$scope.notes = Notes.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.DeleteNote = function (index) {
			var note = $scope.notes[index];
			var dataObj = $.param({
				notes: note.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/removenote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.notes.splice($scope.notes.indexOf(note), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});
}

function Orders_Controller($scope, $http, $mdSidenav, $q, $timeout, $filter) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'order').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$http.get(BASE_URL + 'api/products').then(function (Products) {
		$scope.products = Products.data;
	});

	$http.get(BASE_URL + 'api/leads').then(function (Leads) {
		$scope.leads = Leads.data;
	});


	$scope.GetProduct = (function (search) {
		console.log(search);
		var deferred = $q.defer();
		$timeout(function () {
			deferred.resolve($scope.products);
		}, Math.random() * 500, false);
		return deferred.promise;
	});

	$scope.order = {
		items: [{
			name: new_item,
			product_id: 0,
			code: '',
			description: '',
			quantity: 1,
			unit: item_unit,
			price: 0,
			tax: 0,
			discount: 0,
		}]
	};

	$scope.add = function () {
		$scope.order.items.push({
			name: new_item,
			product_id: 0,
			code: '',
			description: '',
			quantity: 1,
			unit: item_unit,
			price: 0,
			tax: 0,
			discount: 0,
		});
	};

	$scope.remove = function (index) {
		$scope.order.items.splice(index, 1);
	};

	$scope.subtotal = function () {
		var subtotal = 0;
		angular.forEach($scope.order.items, function (item) {
			subtotal += item.quantity * item.price;
		});
		return subtotal.toFixed(2);
	};

	$scope.linediscount = function () {
		var linediscount = 0;
		angular.forEach($scope.order.items, function (item) {
			linediscount += ((item.discount) / 100 * item.quantity * item.price);
		});
		return linediscount.toFixed(2);
	};

	$scope.totaltax = function () {
		var totaltax = 0;
		angular.forEach($scope.order.items, function (item) {
			totaltax += ((item.tax) / 100 * item.quantity * item.price);
		});
		return totaltax.toFixed(2);
	};

	$scope.grandtotal = function () {
		var grandtotal = 0;
		angular.forEach($scope.order.items, function (item) {
			grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
		});
		return grandtotal.toFixed(2);
	};

	$scope.saveAll = function () {
		var dataObj = $.param({
			customer: $scope.customer,
			lead: $scope.lead,
			comment: $scope.comment,
			subject: $scope.subject,
			content: $scope.content,
			date: moment($scope.created).format("YYYY-MM-DD"),
			created: moment($scope.created).format("YYYY-MM-DD"),
			opentill: moment($scope.opentill).format("YYYY-MM-DD"),
			order_type: $scope.order_type,
			status: $scope.status,
			assigned: $scope.assigned,
			sub_total: $scope.subtotal,
			total_discount: $scope.linediscount,
			total_tax: $scope.totaltax,
			total: $scope.grandtotal,
			items: $scope.order.items,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'orders/create';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					console.log(response);
					window.location.href = BASE_URL + 'orders/order/' + response.data;
				},
				function (response) {
					console.log(response);
				}
			);
	};


	$http.get(BASE_URL + 'api/orders').then(function (Orders) {
		$scope.orders = Orders.data;
		$scope.search = {
			subject: '',
		};
		// Filter Buttons //
		$scope.toggleFilter = buildToggler('ContentFilter');

		function buildToggler(navID) {
			return function () {
				$mdSidenav(navID).toggle();

			};
		}
		$scope.close = function () {
			$mdSidenav('ContentFilter').close();
		};
		// Filter Buttons //
		// Filtered Datas
		$scope.filter = {};
		$scope.getOptionsFor = function (propName) {
			return ($scope.orders || []).map(function (item) {
				return item[propName];
			}).filter(function (item, idx, arr) {
				return arr.indexOf(item) === idx;
			}).sort();
		};
		$scope.FilteredData = function (item) {
			// Use this snippet for matching with AND
			var matchesAND = true;
			for (var prop in $scope.filter) {
				if (noSubFilter($scope.filter[prop])) {
					continue;
				}
				if (!$scope.filter[prop][item[prop]]) {
					matchesAND = false;
					break;
				}
			}
			return matchesAND;

		};

		function noSubFilter(subFilterObj) {
			for (var key in subFilterObj) {
				if (subFilterObj[key]) {
					return false;
				}
			}
			return true;
		}
		$scope.updateDropdown = function (_prop) {
				var _opt = this.filter_select,
					_optList = this.getOptionsFor(_prop),
					len = _optList.length;

				if (_opt == 'all') {
					for (var j = 0; j < len; j++) {
						$scope.filter[_prop][_optList[j]] = true;
					}
				} else {
					for (var j = 0; j < len; j++) {
						$scope.filter[_prop][_optList[j]] = false;
					}
					$scope.filter[_prop][_opt] = true;
				}
			}
			// Filtered Datas
		$scope.itemsPerPage = 5;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 5;
			var ps = [];
			var start;

			start = $scope.currentPage;
			//  console.log($scope.pageCount(),$scope.currentPage)
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};

		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};

		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};

		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};

		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};

		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};

		$scope.pageCount = function () {
			return Math.ceil($scope.orders.length / $scope.itemsPerPage) - 1;
		};
	});
}

function Order_Controller($scope, $http, $mdSidenav, $mdDialog, $q, $timeout) {
	"use strict";

	$scope.GeneratePDF = function (ev) {
		$mdDialog.show({
			templateUrl: 'generate-order.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.sendingEmail = false;
	$scope.sendEmail = function() {
		$scope.sendingEmail = true;
		$http.post(BASE_URL + 'orders/send_order_email/' + ORDERID)
			.then(
				function (response) {
					showToast(NTFTITLE, response.data.message, 'success');
					$scope.sendingEmail = false;
				},
				function (response) {
					$scope.sendingEmail = false;
				}
			);
		}

	$scope.CreatePDF = function () {
		$scope.PDFCreating = true;
		$http.post(BASE_URL + 'orders/create_pdf/' + ORDERID)
			.then(
				function (response) {
					console.log(response);
					if (response.data.status === true) {
						$scope.PDFCreating = false;
						$scope.CreatedPDFName = response.data.file_name;
					}
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$http.get(BASE_URL + 'api/order/' + ORDERID).then(function (OrderDetails) {
		$scope.order = OrderDetails.data;


		$scope.Convert = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title('Information!')
				.textContent('Do you want to convert this order to invoice?')
				.ariaLabel('Convert')
				.targetEvent(ORDERID)
				.ok('Convert!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var dataObj = $.param({
					total: $scope.ProjectTotalAmount,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'orders/convert_invoice/' + ORDERID, dataObj, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'invoices/update/' + response.data;
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

		$scope.Update = function () {
			window.location.href = BASE_URL + 'orders/update/' + ORDERID;
		};

		$scope.ViewOrder = function () {
			window.location.href = BASE_URL + 'share/order/' + $scope.order.token;
		};

		$scope.Delete = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title($scope.lang.delete_order)
				.textContent($scope.lang.order_remove_msg)
				.ariaLabel('Delete Order')
				.targetEvent(ORDERID)
				.ok($scope.lang.delete)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'orders/remove/' + ORDERID, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'orders';
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

		$scope.MarkAs = function (id, name) {
			var dataObj = $.param({
				status_id: id,
				order_id: ORDERID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'orders/markas/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: '<b>Order marked as' + ' ' + name + '</b>',
							class_name: 'color success'
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.subtotal = function () {
			var subtotal = 0;
			angular.forEach($scope.order.items, function (item) {
				subtotal += item.quantity * item.price;
			});
			return subtotal.toFixed(2);
		};
		$scope.linediscount = function () {
			var linediscount = 0;
			angular.forEach($scope.order.items, function (item) {
				linediscount += ((item.discount) / 100 * item.quantity * item.price);
			});
			return linediscount.toFixed(2);
		};
		$scope.totaltax = function () {
			var totaltax = 0;
			angular.forEach($scope.order.items, function (item) {
				totaltax += ((item.tax) / 100 * item.quantity * item.price);
			});
			return totaltax.toFixed(2);
		};
		$scope.grandtotal = function () {
			var grandtotal = 0;
			angular.forEach($scope.order.items, function (item) {
				grandtotal += item.quantity * item.price + ((item.tax) / 100 * item.quantity * item.price) - ((item.discount) / 100 * item.quantity * item.price);
			});
			return grandtotal.toFixed(2);
		};

		$http.get(BASE_URL + 'api/products').then(function (Products) {
			$scope.products = Products.data;
		});

		$http.get(BASE_URL + 'api/leads').then(function (Leads) {
			$scope.leads = Leads.data;
		});

		$scope.GetProduct = (function (search) {
			console.log(search);
			var deferred = $q.defer();
			$timeout(function () {
				deferred.resolve($scope.products);
			}, Math.random() * 500, false);
			return deferred.promise;
		});

		$scope.add = function () {
			$scope.order.items.push({
				name: new_item,
				product_id: 0,
				code: '',
				description: '',
				quantity: 1,
				unit: item_unit,
				price: 0,
				tax: 0,
				discount: 0,
			});
		};
		$scope.remove = function (index) {
			var item = $scope.order.items[index];
			$http.post(BASE_URL + 'orders/remove_item/' + item.id)
				.then(
					function (response) {
						console.log(response);
						$scope.order.items.splice(index, 1);
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.ProType = $scope.order.order_type;

		$scope.saveAll = function () {
			var dataObj = $.param({
				customer: $scope.order.customer,
				lead: $scope.order.lead,
				comment: $scope.order.comment,
				subject: $scope.order.subject,
				content: $scope.order.content,
				date: moment($scope.order.created).format("YYYY-MM-DD"),
				created: moment($scope.order.created).format("YYYY-MM-DD"),
				opentill: moment($scope.order.opentill).format("YYYY-MM-DD"),
				order_type: $scope.ProType,
				status: $scope.order.status,
				assigned: $scope.order.assigned,
				sub_total: $scope.subtotal,
				total_discount: $scope.linediscount,
				total_tax: $scope.totaltax,
				total: $scope.grandtotal,
				items: $scope.order.items,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'orders/update/' + ORDERID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						window.location.href = BASE_URL + 'orders/order/' + response.data;
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$scope.ReminderForm = buildToggler('ReminderForm');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}
	$scope.close = function () {
		$mdSidenav('ReminderForm').close();
	};

	$scope.CloseModal = function () {
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'api/products').then(function (Products) {
		$scope.products = Products.data;
	});

	$http.get(BASE_URL + 'api/reminders_by_type/order/' + ORDERID).then(function (Reminders) {
		$scope.in_reminders = Reminders.data;
		$scope.AddReminder = function () {
			var dataObj = $.param({
				description: $scope.reminder_description,
				date: moment($scope.reminder_date).format("YYYY-MM-DD HH:mm:ss"),
				staff: $scope.reminder_staff,
				relation_type: 'order',
				relation: ORDERID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/addreminder';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$scope.in_reminders.push({
							'description': $scope.reminder_description,
							'creator': LOGGEDINSTAFFNAME,
							'avatar': UPIMGURL + LOGGEDINSTAFFAVATAR,
							'staff': LOGGEDINSTAFFNAME,
							'date': $scope.reminder_date,
						});
						$mdSidenav('ReminderForm').close();
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.DeleteReminder = function (index) {
			var reminder = $scope.in_reminders[index];
			var dataObj = $.param({
				reminder: reminder.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/removereminder';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.in_reminders.splice($scope.in_reminders.indexOf(reminder), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/notes/order/' + ORDERID).then(function (Notes) {
		$scope.notes = Notes.data;
		$scope.AddNote = function () {
			var dataObj = $.param({
				description: $scope.note,
				relation_type: 'order',
				relation: ORDERID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/addnote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$('.note-description').val('');
							$scope.note = '';
							$http.get(BASE_URL + 'api/notes/order/' + ORDERID).then(function (Notes) {
								$scope.notes = Notes.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.DeleteNote = function (index) {
			var note = $scope.notes[index];
			var dataObj = $.param({
				notes: note.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/removenote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.notes.splice($scope.notes.indexOf(note), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});
}

function Projects_Controller($scope, $http, $mdSidenav, $filter, $mdDialog) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'project').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$scope.Create = buildToggler('Create');
	$scope.toggleFilter = buildToggler('ContentFilter');

	$scope.projectLoader = true;
	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();
		};
	}
	$scope.close = function () {
		$mdSidenav('Create').close();
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'api/projects').then(function (Projects) {
		$scope.projects = Projects.data;
		$scope.projectLoader = false;
		$scope.saving = false;
		$scope.CreateNew = function () { 
			$scope.saving = true;
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				if ($scope.project.template != true) {
					$scope.project.template = false;
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			if (!$scope.project) {
				var dataObj = $.param({
					name: '',
					customer: '',
					value: '',
					description: '',
					start: moment('').format("YYYY-MM-DD"),
					deadline: moment('').format("YYYY-MM-DD"),
					custom_fields: '',
					template: '',
				});
			} else {
				if ($scope.project.start) {
					$scope.project.start = moment($scope.project.start).format("YYYY-MM-DD")
				}
				if ($scope.project.deadline) {
					$scope.project.deadline = moment($scope.project.deadline).format("YYYY-MM-DD")
				}
				var dataObj = $.param({
					name: $scope.project.name,
					customer: $scope.project.customer,
					value: $scope.project.value,
					tax: $scope.project.tax,
					description: $scope.project.description,
					start: $scope.project.start,
					deadline: $scope.project.deadline,
					custom_fields: $scope.tempArr,
					template: $scope.project.template
				});
			}

			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'projects/create';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							$scope.project.name = '';
							$scope.project.customer = '';
							$scope.project.value = '';
							$scope.project.tax = '';
							$scope.project.description = '';
							$scope.project.start = '';
							$scope.project.deadline = '';
							$mdSidenav('Create').close();
							$http.get(BASE_URL + 'api/projects').then(function (Projects) {
								$scope.projects = Projects.data;
							});
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
						$scope.saving = false;
					},
					function (response) {
						console.log(response);
						showToast(NTFTITLE, 'Error occured!', ' danger');
						$scope.saving = false;
						$http.get(BASE_URL + 'api/projects').then(function (Projects) {
							$scope.projects = Projects.data;
						});
					}
				);
		};

		$scope.markasComplete = function (id) { 
			var confirm = $mdDialog.confirm()
				.title(lang.attention)
				.textContent(lang.project_complete_note)
				.ariaLabel('Convert')
				.targetEvent(id)
				.ok(lang.doIt)
				.cancel(lang.cancel);
			$mdDialog.show(confirm).then(function () {
				var dataObj = $.param({
					status_id: 5,
					project_id: id
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'projects/markas_complete/', dataObj, config)
					.then(
						function (response) {
							if (response.data.success == true) {
								showToast(NTFTITLE, response.data.message, ' success');
							} else {
								showToast(NTFTITLE, response.data.message, ' danger');
							}
							$http.get(BASE_URL + 'api/projects').then(function (Projects) {
								$scope.projects = Projects.data;
							});
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
			});
		};

		$scope.pinnedprojects = Projects.data;

		$scope.CheckPinned = function (index) {
			var project = $scope.projects[index];
			var dataObj = $.param({
				project: project.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'projects/checkpinned', dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: 'Project pinned',
							class_name: 'color success'
						});
						$http.get(BASE_URL + 'api/projects').then(function (Projects) {
							$scope.pinnedprojects = Projects.data;
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.UnPinned = function (id) {
			var dataObj = $.param({
				pinnedproject: id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'projects/unpinned';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: 'Project unpinned',
							class_name: 'color success'
						});
						$http.get(BASE_URL + 'api/projects').then(function (Projects) {
							$scope.pinnedprojects = Projects.data;
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};

		var projectId;
		$scope.copyProjectDialog = function (id) {
			projectId = id
			$mdDialog.show({
				templateUrl: 'copyProjectDialog.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: ''
			});
		};

		$scope.copyProjectConfirm = function () {
			if (!$scope.copy) {
				var dataObj = $.param({
					services: false,
					expenses: false,
					milestones: false,
					tasks: false,
					peoples: false,
					files: false,
					notes: false,
					customer_id: '',
					startdate: '',
					enddate: ''
				});
			} else {
				if (!$scope.copy.service) {
					$scope.copy.service = false;
				}
				if (!$scope.copy.expenses) {
					$scope.copy.expenses = false;
				}
				if (!$scope.copy.milestones) {
					$scope.copy.milestones = false;
				}
				if (!$scope.copy.tasks) {
					$scope.copy.tasks = false;
				}
				if (!$scope.copy.peoples) {
					$scope.copy.peoples = false;
				}
				if (!$scope.copy.files) {
					$scope.copy.files = false;
				}
				if (!$scope.copy.notes) {
					$scope.copy.notes = false;
				}
				var dataObj = $.param({
					services: $scope.copy.service,
					expenses: $scope.copy.expenses,
					milestones: $scope.copy.milestones,
					tasks: $scope.copy.tasks,
					peoples: $scope.copy.peoples,
					files: $scope.copy.files,
					notes: $scope.copy.notes,
					customer_id: $scope.copy.customer,
					startdate: moment($scope.copy.start).format("YYYY-MM-DD"),
					enddate: moment($scope.copy.end).format("YYYY-MM-DD"),
				});
			}
				
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			if (!projectId) {
				$.gritter.add({
					title: '<b>' + NTFTITLE + '</b>',
					text: $scope.lang.errormessage,
					class_name: 'color danger'
				});
			} else {
				$mdDialog.show({
					templateUrl: 'processing.html',
					scope: $scope,
					preserveScope: true,
					targetEvent: ''
				});
				var posturl = BASE_URL + 'projects/copyProject/' + projectId;
				$http.post(posturl, dataObj, config)
					.then(
						function (response) {
							if (response.data.success == true) {
								$mdDialog.hide();
								$.gritter.add({
									title: '<b>' + NTFTITLE + '</b>',
									text: response.data.message,
									class_name: 'color success'
								});
								$http.get(BASE_URL + 'api/projects').then(function (Projects) {
									$scope.projects = Projects.data;
								});
							} else {
								$mdDialog.hide();
								$.gritter.add({
									title: '<b>' + NTFTITLE + '</b>',
									text: $scope.lang.errormessage,
									class_name: 'color danger'
								});
							}
							console.log(response);
						}, function() {

						}
					);
			}
		};

		$scope.itemsPerPage = 6;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 6;
			var ps = [];
			var start;

			start = $scope.currentPage;
			//  console.log($scope.pageCount(),$scope.currentPage)
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};
		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};
		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};
		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};
		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};
		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};
		$scope.pageCount = function () {
			return Math.ceil($scope.projects.length / $scope.itemsPerPage) - 1;
		};
	});
}

function Project_Controller($scope, $http, $mdSidenav, $mdDialog, $filter, $sce) {
	"use strict";

	$scope.NewMilestone = buildToggler('NewMilestone');
	$scope.NewTask = buildToggler('NewTask');
	$scope.NewExpense = buildToggler('NewExpense');
	$scope.NewService = buildToggler('NewService');
	$scope.Update = buildToggler('Update');
	$scope.NewTicket = buildToggler('NewTicket');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('NewMilestone').close();
		$mdSidenav('NewTask').close();
		$mdSidenav('NewExpense').close();
		$mdSidenav('NewService').close();
		$mdSidenav('Update').close();
		$mdSidenav('UpdateService').close();
		$mdSidenav('NewTicket').close();
		$mdDialog.hide();
		$scope.invoiceButton = false;
	};

	$scope.UploadFile = function (ev) {
		$mdDialog.show({
			templateUrl: 'addfile-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.AddTask = function () {
		if ($scope.isPublic === true) {
			$scope.isPublicValue = 1;
		} else {
			$scope.isPublicValue = 0;
		}
		if ($scope.isBillable === true) {
			$scope.isBillableValue = 1;
		} else {
			$scope.isBillableValue = 0;
		}
		if ($scope.isVisible === true) {
			$scope.isVisibleValue = 1;
		} else {
			$scope.isVisibleValue = 0;
		}

		if (!$scope.newtask) {
			var dataObj = $.param({
				name: '',
				hourlyrate: '',
				assigned: '',
				priority: '',
				milestone: '',
				public: '',
				billable: '',
				visible: '',
				startdate: '',
				duedate: '',
				description: '',
			});
		} else {
			var dataObj = $.param({
				name: $scope.newtask.name,
				hourlyrate: $scope.newtask.hourlyrate,
				assigned: $scope.newtask.assigned,
				priority: $scope.newtask.priority,
				milestone: $scope.newtask.milestone,
				public: $scope.isPublicValue,
				billable: $scope.isBillableValue,
				visible: $scope.isVisibleValue,
				startdate: moment($scope.newtask.startdate).format("YYYY-MM-DD"),
				duedate: moment($scope.newtask.duedate).format("YYYY-MM-DD"),
				description: $scope.newtask.description,
			});
		}
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'projects/addtask/' + PROJECTID;
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					if (response.data.success == true) {
						$mdSidenav('NewTask').close();
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: response.data.message,
							class_name: 'color success'
						});
						$http.get(BASE_URL + 'api/project/' + PROJECTID).then(function (Project) {
							$scope.project = Project.data;
						});
						$scope.newtask.name = '';
						$scope.newtask.description = '';
					} else {
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: response.data.message,
							class_name: 'color danger'
						});
					}
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'project/' + PROJECTID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$scope.projectLoader = true;
	$http.get(BASE_URL + 'api/project/' + PROJECTID).then(function (Project) {
		$scope.project = Project.data;
		$scope.projectLoader = false;

		$scope.AddProjectMember = function () {
			var dataObj = $.param({
				project: PROJECTID,
				staff: $scope.insertedStaff
			});
			console.log(dataObj, "hi")
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'projects/addmember';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$mdDialog.hide(); 
							$scope.project.members.push({
								'id': response.data.member.staffavatar,
								'staffname': response.data.member.staffname,
								'staffavatar': response.data.member.staffavatar,
								'email': response.data.member.email,
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.adding = false;
		$scope.AddExpense = function () {
			$scope.adding = true;
			if (!$scope.newexpense) {
				var dataObj = $.param({
					title: '',
					amount: '',
					date: '',
					category: '',
					account: '',
					description: '',
					customer: '',
				});
			} else {
				var dataObj = $.param({
					name: $scope.project.name,
					title: $scope.newexpense.title,
					amount: $scope.newexpense.amount,
					date: moment($scope.newexpense.date).format("YYYY-MM-DD"),
					category: $scope.newexpense.category,
					account: $scope.newexpense.account,
					description: $scope.newexpense.description,
					customer: $scope.project.customer_id,
				});
			}
				
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};

			var posturl = BASE_URL + 'projects/addexpense/' + PROJECTID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.adding = false;
						if (response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							$mdSidenav('NewExpense').close();
							$http.get(BASE_URL + 'api/expenses_by_relation/project/' + PROJECTID).then(function (Expenses) {
								$scope.expenses = Expenses.data;
								$scope.TotalExpenses = function () {
									return $scope.expenses.reduce(function (total, expense) {
										return total + (expense.amount * 1 || 0);
									}, 0);
								};
								$scope.billedexpenses = $filter('filter')($scope.expenses, {
									billstatus_code: "true"
								});
								$scope.BilledExpensesTotal = function () {
									return $scope.billedexpenses.reduce(function (total, expense) {
										return total + (expense.amount * 1 || 0);
									}, 0);
								};
								$scope.unbilledexpenses = $filter('filter')($scope.expenses, {
									billstatus_code: "false"
								});
								$scope.UnBilledExpensesTotal = function () {
									return $scope.unbilledexpenses.reduce(function (total, expense) {
										return total + (expense.amount * 1 || 0);
									}, 0);
								};
							});
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.adding = false;
						console.log(response);
					}
				);
		};

		$scope.createTicket = function () {
			if (!$scope.ticket) {
				var dataObj = $.param({
					subject: '',
					customer: '',
					contact: '',
					department: '',
					priority: '',
					message: '',
				});
			} else {
				var dataObj = $.param({
					subject: $scope.ticket.subject,
					customer: $scope.ticket.customer.customer_id,
					contact: $scope.ticket.contact,
					department: $scope.ticket.department,
					priority: $scope.ticket.priority,
					message: $scope.ticket.message,
				});
			}
				
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'projects/createticket/' + PROJECTID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$mdSidenav('NewTicket').close();
							$http.get(BASE_URL + 'projects/tickets/' + PROJECTID).then(function (Tickets) {
								$scope.tickets = Tickets.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
							
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$http.get(BASE_URL + 'projects/tickets/' + PROJECTID).then(function (Tickets) {
			$scope.tickets = Tickets.data;

			$scope.viewTicket = function(index) {
				$scope.ticket = $scope.tickets[index];
				$mdDialog.show({
					templateUrl: 'ticketDialog.html',
					scope: $scope,
					preserveScope: true,
					targetEvent: $scope.ticket.id
				});
			}
		});

		$scope.TicketMarkAs = function (id, name, TICKETID) {
			var dataObj = $.param({
				status_id: id,
				ticket_id: TICKETID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'projects/ticket_markas/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: '<b>Ticket marked as' + ' ' + name + '</b>',
							class_name: 'color success'
						});
						$http.get(BASE_URL + 'projects/tickets/' + PROJECTID).then(function (Tickets) {
							$scope.tickets = Tickets.data;
							$scope.ticketsData = Tickets.data;
							for(var i = 0; i < $scope.ticketsData.length; i++) {
								if ($scope.ticketsData[i].id == TICKETID) {
									$scope.ticket = $scope.ticketsData[i];
									break;
								}
							}
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.DeleteTicket = function (TICKETID) {
			$mdDialog.hide();
			var confirm = $mdDialog.confirm()
				.title('Attention!')
				.textContent('Do you confirm the deletion of all data belonging to this ticket?')
				.ariaLabel('Delete Ticket')
				.targetEvent(TICKETID)
				.ok('Do it!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'projects/remove_ticket/' + TICKETID, config)
					.then(
						function (response) {
							$mdDialog.hide();
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data,
								class_name: 'color success'
							});
							$http.get(BASE_URL + 'projects/tickets/' + PROJECTID).then(function (Tickets) {
								$scope.tickets = Tickets.data;
							});
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

		var SERVICEID;
		$http.get(BASE_URL + 'projects/get_project_services/' + PROJECTID).then(function (Services) {
			$scope.projectservices = Services.data;
			$scope.DeleteService = function(index) {
				$scope.servicesData = $scope.projectservices[index];
				var id = $scope.servicesData['serviceid'];
				console.log(id, $scope.servicesData)
				var confirm = $mdDialog.confirm()
					.title('Attention!')
					.textContent('Do you confirm the deletion of this service?')
					.ariaLabel('Delete Service')
					.targetEvent(PROJECTID)
					.ok('Do it!')
					.cancel('Cancel');

				$mdDialog.show(confirm).then(function () {
					var config = {
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
						}
					};
					$http.post(BASE_URL + 'projects/removeService/' + id, config)
						.then(
							function (response) {
								console.log(response);
								$http.get(BASE_URL + 'projects/get_project_services/' + PROJECTID).then(function (Services) {
									$scope.projectservices = Services.data;
								});
							},
							function (response) {
								console.log(response);
							}
						);

				}, function () {
					//
				});
			}

			$scope.UpdateService = function(index) {
				SERVICEID = $scope.projectservices[index].serviceid;
				$scope.updateservice = $scope.projectservices[index];
				$scope.updateservice.category = $scope.updateservice.categoryid;
				$scope.getProducts($scope.updateservice.category);
				$scope.updateservice.product = $scope.updateservice.productid;
				$scope.updateservice.productname = $scope.updateservice.servicename;
				$scope.updateservice.price = $scope.updateservice.serviceprice;
				$scope.updateservice.tax = $scope.updateservice.servicetax;
				$scope.updateservice.unit = $scope.updateservice.unit;
				$scope.updateservice.description = $scope.updateservice.servicedescription;
				$scope.updateservice.quantity = $scope.updateservice.quantity;
				$mdSidenav('UpdateService').toggle();
			}
		});

		$http.get(BASE_URL + 'products/get_product_categories').then(function (Categories) {
			$scope.productcategories = Categories.data;
			$scope.productFound = false;
		});

		var products;
		$scope.getProducts = function(id) {
			$http.get(BASE_URL + 'projects/get_products_by_category/' + id).then(function (Products) {
				$scope.categoriesproduct = Products.data;
				products = Products.data;
				if (Products.data.length > 0) {
					$scope.productFound = false;
				} else {
					$scope.productFound = true;
				}
			});
		}

		$scope.getProductData = function(index) {
			if (products && index) {
				for (var i = 0; i < products.length; i++) {
					if (products[i].id == index) {
						$scope.newservice.productname = products[i].productname;
						$scope.newservice.tax = products[i].vat;
						$scope.newservice.price = products[i].purchase_price;
						$scope.newservice.description = products[i].description;
						$scope.newservice.quantity = products[i].quantity;

						$scope.updateservice.productname = products[i].productname;
						$scope.updateservice.price = products[i].purchase_price;
						$scope.updateservice.tax = products[i].vat;
						$scope.updateservice.unit = products[i].unit;
						$scope.updateservice.description = products[i].description;
						$scope.updateservice.quantity = products[i].quantity;
						if (parseInt(products[i].purchase_price) == 0) {
							$scope.newservice.price = products[i].sale_price;
							$scope.updateservice.price = products[i].sale_price;
						}
						break;
					}
				}
			}
		}

		$scope.AddService = function() {
			console.log($scope.newservice);
			if ($scope.newservice == undefined || !$scope.newservice) {
				var dataObj = $.param({
					categoryid: '',
					productid: '',
					servicename: '',
					serviceprice: '',
					servicetax: '',
					quantity: '',
					unit: '',
					servicedescription: '',
				});
			} else {
				var dataObj = $.param({
					categoryid: $scope.newservice.category,
					productid: $scope.newservice.product,
					servicename: $scope.newservice.productname,
					serviceprice: $scope.newservice.price,
					servicetax: $scope.newservice.tax,
					quantity: $scope.newservice.quantity,
					unit: $scope.newservice.unit,
					servicedescription: $scope.newservice.description,
					projectid: PROJECTID,
				});
			}

			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'projects/addservice/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$mdSidenav('NewService').close();
							$http.get(BASE_URL + 'projects/get_project_services/' + PROJECTID).then(function (Services) {
								$scope.projectservices = Services.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);
		}

		$scope.SaveService = function() {
			if ($scope.updateservice == undefined || !$scope.updateservice) {
				var dataObj = $.param({
					categoryid: '',
					productid: '',
					servicename: '',
					serviceprice: '',
					servicetax: '',
					unit: '',
					servicedescription: '',
				});
			} else {
				var dataObj = $.param({
					categoryid: $scope.updateservice.category,
					productid: $scope.updateservice.product,
					servicename: $scope.updateservice.productname,
					serviceprice: $scope.updateservice.price,
					servicetax: $scope.updateservice.tax,
					quantity: $scope.updateservice.quantity,
					unit: $scope.updateservice.unit,
					servicedescription: $scope.updateservice.description,
					projectid: PROJECTID,
				});
			}

			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'projects/updateservice/'+SERVICEID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$mdSidenav('UpdateService').close();
							$http.get(BASE_URL + 'projects/get_project_services/' + PROJECTID).then(function (Services) {
								$scope.projectservices = Services.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);
		}

		$scope.saving = false;
		$scope.UpdateProject = function () {
			$scope.saving = true;
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});

			if (!$scope.project) {
				var dataObj = $.param({
					name: '',
					customer: '',
					value: '',
					description: '',
					start: moment('').format("YYYY-MM-DD"),
					deadline: moment('').format("YYYY-MM-DD"),
					custom_fields: '',
				});
			} else {
				if ($scope.project.template != true || !$scope.project.template) {
					$scope.project.template = false;
				} else {
					$scope.project.template = true;
				}
				if ($scope.project.start) {
					$scope.project.start = moment($scope.project.start).format("YYYY-MM-DD")
				}
				if ($scope.project.deadline) {
					$scope.project.deadline = moment($scope.project.deadline).format("YYYY-MM-DD")
				}
				var dataObj = $.param({
					name: $scope.project.name,
					customer: $scope.project.customer_id,
					value: $scope.project.value,
					tax: $scope.project.tax,
					description: $scope.project.description,
					start: $scope.project.start,
					deadline: $scope.project.deadline,
					custom_fields: $scope.tempArr,
					template: $scope.project.template
				});
			}

			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'projects/update/' + PROJECTID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.saving = false;
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$mdSidenav('Update').close();
							$http.get(BASE_URL + 'api/project/'+PROJECTID).then(function (Projects) {
								$scope.project = Projects.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						$scope.saving = false;
						console.log(response);
					}
				);
		};
		
		$scope.projectmembers = $scope.project.members;
		$scope.UnlinkMember = function (index) { 
			var link = $scope.projectmembers[index];
			var confirm = $mdDialog.confirm()
				.title(langs.attention)
				.textContent(langs.remove_staff)
				.targetEvent(PROJECTID)
				.ok(langs.doIt)
				.cancel(langs.cancel);

			$mdDialog.show(confirm).then(function () {
				var linkid = link.id;
				var dataObj = $.param({
					linkid: linkid
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'projects/unlinkmember/' + linkid, dataObj, config)
					.then(
						function (response) {
							showToast(NTFTITLE, response.data.message, ' success');
							$http.get(BASE_URL + 'api/project/' + PROJECTID).then(function (Project) {
								$scope.project = Project.data;
							});
						},
						function (response) {
							console.log(response);
						}
					);
				});
		};

		$http.get(BASE_URL + 'api/projecttimelogs/' + PROJECTID).then(function (TimeLogs) {
			$scope.timelogs = TimeLogs.data;
			$scope.getTotal = function () {
				var TotalTime = 0;
				for (var i = 0; i < $scope.timelogs.length; i++) {
					var timelog = $scope.timelogs[i];
					TotalTime += (timelog.timed);
				}
				return TotalTime;
			};
			$scope.ProjectTotalAmount = function () {
				var TotalAmount = 0;
				for (var i = 0; i < $scope.timelogs.length; i++) {
					var timelog = $scope.timelogs[i];
					TotalAmount += (timelog.amount);
				}
				return TotalAmount;
			};
		});

		$scope.InsertMember = function (ev) {
			$mdDialog.show({
				templateUrl: 'insert-member-template.html', 
				scope: $scope,
				preserveScope: true,
				targetEvent: ev
			});
		};

		$scope.ConvertDialog = function () {
			$scope.invoiceButton = false;
			$mdDialog.show({
				templateUrl: 'convertDialog.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: ''
			});
		}

		$scope.Convert = function() {
			$scope.invoiceButton = true;
			var dataObj = $.param({
				total: $scope.ProjectTotalAmount,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'projects/convert/' + PROJECTID, dataObj, config)
				.then(
					function (response) { 
						console.log(response);
						window.location.href = BASE_URL + 'invoices/invoice/' + response.data;
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.ConvertWithProjectValue = function () {
			$scope.invoiceButton = true;
			var dataObj = $.param({
				total: $scope.ProjectTotalAmount, 
				cost: $scope.project.value,
				name: $scope.project.name,
				description: $scope.project.description,
				tax: $scope.project.tax,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'projects/convertwithcost/' + PROJECTID, dataObj, config)
				.then(
					function (response) { 
						console.log(response);
						window.location.href = BASE_URL + 'invoices/invoice/' + response.data;
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.Delete = function () {
			$mdDialog.show({
				templateUrl: 'delete_project.html',
				scope: $scope,
				preserveScope: true,
			});
		};

		$scope.deletingProject = false;
		$scope.DeleteProject = function () {
			$scope.deletingProject = true;
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'projects/remove/' + PROJECTID, config)
				.then(
					function (response) {
						console.log(response);
						window.location.href = BASE_URL + 'projects';
						$scope.deletingProject = false;
					},
					function (response) {
						console.log(response);
						$scope.deletingProject = false;
					}
				);
		};
	});

	$scope.MarkAs = function (id, name) {
		var dataObj = $.param({
			status_id: id,
			project_id: PROJECTID,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'projects/markas/';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$http.get(BASE_URL + 'api/project/' + PROJECTID).then(function (Project) {
						$scope.project = Project.data;
					});
					showToast(NTFTITLE, langs.marked+' <b>'+name+'</b>', ' success');
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$http.get(BASE_URL + 'api/projectmilestones/' + PROJECTID).then(function (Milestones) {
		$scope.milestones = Milestones.data;

		$scope.addingMilestone = false;
		$scope.AddMilestone = function () {
			$scope.addingMilestone = true;
			if (!$scope.amilestone) {
				var dataObj = $.param({
					order: '',
					name: '',
					description: '',
					duedate: '',
				});
			} else {
				if ($scope.amilestone.duedate) {
					$scope.amilestone.duedate = moment($scope.amilestone.duedate).format("YYYY-MM-DD HH:mm:ss");
				}
				var dataObj = $.param({
					order: $scope.amilestone.order,
					name: $scope.amilestone.name,
					description: $scope.amilestone.description,
					duedate: $scope.amilestone.duedate,
				});
			}
				
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'projects/addmilestone/' + PROJECTID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.addingMilestone = false;
						if (response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							$mdSidenav('NewMilestone').close();
							$http.get(BASE_URL + 'api/projectmilestones/' + PROJECTID).then(function (Milestones) {
								$scope.milestones = Milestones.data;
							});
							$scope.amilestone.order = '';
							$scope.amilestone.name = '';
							$scope.amilestone.description = '';
							$scope.amilestone.duedate = '';
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.addingMilestone = false;
					}
				);
		};

		$scope.ShowMilestone = function (index) {
			console.log(index)
			var milestone = $scope.milestones[index];
			$mdDialog.show({
				contentElement: '#ShowMilestone-' + milestone.id,
				parent: angular.element(document.body),
				targetEvent: index,
				clickOutsideToClose: true
			});
		};

		$scope.savingMilestone = false;
		$scope.UpdateMilestone = function (index) {
			$scope.savingMilestone = true;
			var milestone = $scope.milestones[index];
			var milestone_id = milestone.id;
			$scope.milestone = milestone;
			if ($scope.milestone.duedate) {
				$scope.milestone.duedate = moment($scope.milestone.duedate).format("YYYY-MM-DD HH:mm:ss");
			}
			var dataObj = $.param({
				order: $scope.milestone.order,
				name: $scope.milestone.name,
				description: $scope.milestone.description,
				duedate: $scope.milestone.duedate,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'projects/updatemilestone/' + milestone_id;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.savingMilestone = false;
						if (response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							$http.get(BASE_URL + 'api/projectmilestones/' + PROJECTID).then(function (Milestones) {
								$scope.milestones = Milestones.data;
							});
							$scope.milestone.order = '';
							$scope.milestone.name = '';
							$scope.milestone.description = '';
							$scope.milestone.duedate = '';
							$mdDialog.hide();
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.savingMilestone = false;
					}
				);
		};

		$scope.RemoveMilestone = function (index) {
			var confirm = $mdDialog.confirm()
				.title(langs.attention)
				.textContent(langs.delete_milestone)
				.ariaLabel('Convert')
				.targetEvent(index)
				.ok(langs.doIt)
				.cancel(langs.cancel);
			$mdDialog.show(confirm).then(function () {
				var milestone = $scope.milestones[index];
				var dataObj = $.param({
					milestone: milestone.id
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				var posturl = BASE_URL + 'projects/removemilestone';
				$http.post(posturl, dataObj, config)
					.then(
						function (response) {
							$scope.milestones.splice($scope.milestones.indexOf(milestone), 1);
							console.log(response);
						},
						function (response) {
							console.log(response);
						}
					);
			}, function () {
			});
		};

	});

	$http.get(BASE_URL + 'api/reminders_by_type/project/' + PROJECTID).then(function (Reminders) {
		$scope.in_reminders = Reminders.data;
	});

	$scope.editNote = false;
	$scope.saveNote = false;
	$scope.addNote = false;
	$http.get(BASE_URL + 'api/notes/project/' + PROJECTID).then(function (Notes) {
		$scope.notes = Notes.data;
		$scope.AddNote = function () {
			$scope.addNote = true;
			var dataObj = $.param({
				description: $scope.note,
				relation_type: 'project',
				relation: PROJECTID,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/addnote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.addNote = false;
						if (response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							$('.note-description').val('');
							$scope.note = '';
							$http.get(BASE_URL + 'api/notes/project/' + PROJECTID).then(function (Notes) {
								$scope.notes = Notes.data;
							});
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.addNote = false;
					}
				);
		};

		$scope.EditNote = function (index) {
			var note = $scope.notes[index];
			$scope.editNote = true;
			$scope.edit_note = note.description;
			$scope.edit_note_id = note.id;
			$('#note_focus').focus();
			$('html, body').animate({
				scrollTop: $("#note_focus").offset().top
			}, 1000);
		}

		$scope.SaveNote = function () {
			$scope.saveNote = true;
			var id = $scope.edit_note_id;
			if (id) {
				var dataObj = $.param({
					description: $scope.edit_note,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				var posturl = BASE_URL + 'trivia/updatenote/' + id;
				$http.post(posturl, dataObj, config)
					.then(
						function (response) {
							$scope.editNote = false;
							$scope.saveNote = false;
							$scope.edit_note = '';
							$http.get(BASE_URL + 'api/notes/project/' + PROJECTID).then(function (Notes) {
								$scope.notes = Notes.data;
							});
							showToast(NTFTITLE, response.data, ' success');
						},
						function (response) {
							$scope.editNote = false;
							$scope.saveNote = false;
						}
					);
			} else {
				$scope.editNote = false;
			}
		};

		$scope.modifyNote = false;
		$scope.DeleteNote = function (index) {
			$scope.modifyNote = true;
			var note = $scope.notes[index];
			var dataObj = $.param({
				notes: note.id
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'trivia/removenote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.modifyNote = false;
						$scope.notes.splice($scope.notes.indexOf(note), 1);
					},
					function (response) {
						$scope.modifyNote = false;
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/expenses_by_relation/project/' + PROJECTID).then(function (Expenses) {
		$scope.expenses = Expenses.data;
		$scope.TotalExpenses = function () {
			return $scope.expenses.reduce(function (total, expense) {
				return total + (expense.amount * 1 || 0);
			}, 0);
		};

		$scope.billedexpenses = $filter('filter')($scope.expenses, {
			billstatus_code: "true"
		});
		$scope.BilledExpensesTotal = function () {
			return $scope.billedexpenses.reduce(function (total, expense) {
				return total + (expense.amount * 1 || 0);
			}, 0);
		};

		$scope.unbilledexpenses = $filter('filter')($scope.expenses, {
			billstatus_code: "false"
		});
		$scope.UnBilledExpensesTotal = function () {
			return $scope.unbilledexpenses.reduce(function (total, expense) {
				return total + (expense.amount * 1 || 0);
			}, 0);
		};

		$scope.viewInvoice = function (index) {
			$scope.expense = $scope.expenses[index];
			$mdDialog.show({
				templateUrl: 'expenseDialog.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: $scope.expense.id
			});
		};
	});

	$scope.projectFiles = true;
	$http.get(BASE_URL + 'api/projectfiles/' + PROJECTID).then(function (Files) {
		$scope.files = Files.data;
		$scope.projectFiles = false;

		$scope.itemsPerPage = 6;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 6;
			var ps = [];
			var start;

			start = $scope.currentPage;
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}
			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};
		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};
		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};
		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};
		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};
		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};
		$scope.pageCount = function () {
			return Math.ceil($scope.files.length / $scope.itemsPerPage) - 1;
		};
		
		$scope.ViewFile = function(index, image) {
			$scope.file = $scope.files[index];
			$mdDialog.show({
				templateUrl: 'view_image.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: $scope.file.id
			});
		}

		$scope.DeleteFile = function(id) {
			var confirm = $mdDialog.confirm()
				.title($scope.lang.delete_file_title)
				.textContent($scope.lang.delete_file_message)
				.ariaLabel($scope.lang.delete_file_title)
				.targetEvent(PROJECTID)
				.ok($scope.lang.delete)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'projects/delete_file/' + id, config)
					.then(
						function (response) {
							if(response.data.success == true) {
								showToast(NTFTITLE, response.data.message, ' success');
								$http.get(BASE_URL + 'api/projectfiles/' + PROJECTID).then(function (Files) {
									$scope.files = Files.data;
								});
							} else {
								showToast(NTFTITLE, response.data.message, ' danger');
							}
						},
						function (response) {
							console.log(response);
						}
					);

			}, function() {
				//
			});
		};
	});

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});

	$http.get(BASE_URL + 'api/expensescategories').then(function (Epxensescategories) {
		$scope.expensescategories = Epxensescategories.data;
	});

}

function Tickets_Controller($scope, $http, $mdSidenav) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'ticket').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = ($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$scope.Create = buildToggler('Create');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('Create').close();
	};

	$scope.ticketsLoader = true;
	$http.get(BASE_URL + 'api/tickets').then(function (Tickets) {
		$scope.tickets = Tickets.data;
		$scope.ticketsLoader = false;
		$scope.GoTicket = function (TICKETID) {
			window.location.href = BASE_URL + 'tickets/ticket/' + TICKETID;
		};
		$scope.search = {
			subject: '',
			message: ''
		};

		$scope.itemsPerPage = 5;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 5;
			var ps = [];
			var start;

			start = $scope.currentPage;
			//  console.log($scope.pageCount(),$scope.currentPage)
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};

		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};

		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};

		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};

		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};

		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};

		$scope.pageCount = function () {
			return Math.ceil($scope.tickets.length / $scope.itemsPerPage) - 1;
		};
	});

	$scope.ShowKanban = function () {
		$scope.KanbanBoard = true;
	};

	$scope.HideKanban = function () {
		$scope.KanbanBoard = false;
	};

	$http.get(BASE_URL + 'api/customers').then(function (Customers) {
		$scope.customers = Customers.data;
	});

	$http.get(BASE_URL + 'api/contacts').then(function (Contacts) {
		$scope.contacts = Contacts.data;
	});
}

function Ticket_Controller($scope, $http, $mdDialog) {
	"use strict";

	$scope.close = function () {
		$mdDialog.hide();
	};

	$scope.AssigneStaff = function (ev) {
		$mdDialog.show({
			templateUrl: 'insert-member-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.ticketsLoader = true;
	$http.get(BASE_URL + 'api/ticket/' + TICKETID).then(function (TicketDetails) {
		$scope.ticket = TicketDetails.data;
		$scope.ticketsLoader = false;
		$scope.AssignStaff = function () {
			var dataObj = $.param({
				staff: $scope.AssignedStaff,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'tickets/assign_staff/' + TICKETID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$mdDialog.hide();
						$scope.ticket.assigned_staff_name = response.data;
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.Reply = function () {
			var dataObj = $.param({
				message: $scope.reply.message,
				attachment: $scope.reply.attachment,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'tickets/reply/' + TICKETID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$scope.ticket.replies.push({
							'message': $scope.reply.message,
							'name': LOGGEDINSTAFFNAME,
							'date': new Date(),
							'attachment': $scope.reply.attachment,
						});
						$scope.reply.attachment = '';
						$scope.reply.message = '';
					},
					function (response) {
						console.log(response);
					}
				);
		};
		$scope.Delete = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title('Attention!')
				.textContent('Do you confirm the deletion of all data belonging to this ticket?')
				.ariaLabel('Delete Ticket')
				.targetEvent(TICKETID)
				.ok('Do it!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'tickets/remove/' + TICKETID, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'tickets';
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};
	});

	$scope.MarkAs = function (id, name) {
		var dataObj = $.param({
			status_id: id,
			ticket_id: TICKETID,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'tickets/markas/';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					console.log(response);
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: '<b>Ticket marked as' + ' ' + name + '</b>',
						class_name: 'color success'
					});
				},
				function (response) {
					console.log(response);
				}
			);
	};

}

function Products_Controller($scope, $http, $mdSidenav, $filter, $mdDialog) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'product').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});

	$scope.Create = buildToggler('Create');
	$scope.toggleFilter = buildToggler('ContentFilter');
	$scope.CreateCategory = buildToggler('CreateCategory');
	$scope.ImportProductsNav = buildToggler('ImportProductsNav');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();
		};
	}

	$scope.close = function () {
		$mdSidenav('Create').close();
		$mdSidenav('ContentFilter').close();
		$mdSidenav('CreateCategory').close();
		$mdSidenav('ImportProductsNav').close();
	};

	var cdata;
	$http.get(BASE_URL + 'products/categories/').then(function (Data) {
		cdata = Data.data;
		var data = [];
		for(var i = 0; i<cdata.length; i++){
			data.push([cdata[i].name,parseInt(cdata[i].y)]);
		}

		Highcharts.chart('container', {
			chart: {
				polar: true,
				plotBackgroundColor: '#f3f3f3',
				plotBorderWidth: 0,
				plotShadow: false
			},
			title: { 
				text: 'Products<br>Category',
				align: 'center',
				verticalAlign: 'middle',
				y: -18
			},
			tooltip: {
				pointFormat: '<b>{point.y}</b>'
			},
			credits: {
				enabled: false
			},
			plotOptions: {
				pie: {
					dataLabels: {
						enabled: true,
						distance: -50,
						style: {
							fontWeight: 'bold',
							color: 'white'
						}
					},
					// startAngle: -90,
					// endAngle: 90,
					center: ['50%', '47%'],
					size: '100%'
				}
			},
			series: [
			{
				type: 'pie',
				name: '',
				innerSize: '42%',
				data: data
			}],
			exporting: {
				buttons: {
					contextButton: {
						menuItems: ['downloadPNG', 'downloadSVG','downloadPDF', 'downloadCSV', 'downloadXLS']
					}
				}
			}
		});
		function redrawchart(){
	        var chart = $('#container').highcharts();
	        var w = $('#container').closest(".wrapper").width()
	        chart.setSize(       
	            w,w * (3/4),false
	        );
	     }
	    
	    $(window).resize(redrawchart);
	    redrawchart();
	});

	$http.get(BASE_URL + 'api/products').then(function (Products) {
		$scope.products = Products.data;

		$http.get(BASE_URL + 'products/get_product_categories').then(function (Categories) {
			$scope.category = Categories.data;
			$scope.NewCategory = function () {
				var confirm = $mdDialog.prompt()
					.title('New Product Category')
					.textContent('Please type category name')
					.placeholder('Category Name')
					.ariaLabel('Category Name')
					.initialValue('')
					.required(true)
					.ok('Add!')
					.cancel('Cancel');

				$mdDialog.show(confirm).then(function (result) {
					var dataObj = $.param({
						name: result,
					});
					var config = {
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
						}
					};
					$http.post(BASE_URL + 'products/add_category/', dataObj, config)
						.then(
							function (response) {
								if (response.data.success == true) {
									$.gritter.add({
										title: '<b>' + NTFTITLE + '</b>',
										text: response.data.message,
										class_name: 'color success'
									});
								} else {
									$.gritter.add({
										title: '<b>' + NTFTITLE + '</b>',
										text: response.data.message,
										class_name: 'color danger'
									});
								}
								$http.get(BASE_URL + 'products/get_product_categories').then(function (Categories) {
									$scope.category = Categories.data;
								});
							},
							function (response) {
								console.log(response);
							}
						);
				}, function () {

				});
			};

			$scope.EditCategory = function (id, name, event) {
				var confirm = $mdDialog.prompt()
					.title('Edit Category Name')
					.textContent('You can change product category name.')
					.placeholder('Category name')
					.ariaLabel('Category Name')
					.initialValue(name)
					.targetEvent(event)
					.required(true)
					.ok('Save')
					.cancel('Cancel');
				$mdDialog.show(confirm).then(function (result) {
					var dataObj = $.param({
						name: result,
					});
					var config = {
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
						}
					};
					$http.post(BASE_URL + 'products/update_category/' + id, dataObj, config)
						.then(
							function (response) {
								if (response.data.success == true) {
									$.gritter.add({
										title: '<b>' + NTFTITLE + '</b>',
										text: response.data.message,
										class_name: 'color success'
									});
								}
								$http.get(BASE_URL + 'products/get_product_categories').then(function (Categories) {
									$scope.category = Categories.data;
								});
							},
							function () {
							}
						);
				}, function () {
				});
			};

			$scope.DeleteProductCategory = function (index) {
				var name = $scope.category[index];
				var confirm = $mdDialog.confirm()
					.title('Attention!')
					.textContent('Do you confirm the deletion of this category?')
					.ariaLabel('Delete Category')
					.targetEvent(name.id)
					.ok('Do it!')
					.cancel('Cancel');

				$mdDialog.show(confirm).then(function () {
					var config = {
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
						}
					};
					$http.post(BASE_URL + 'products/remove_category/' + name.id, config)
						.then(
							function (response) {
								if (response.data.success == true) {
									$.gritter.add({
										title: '<b>' + NTFTITLE + '</b>',
										text: response.data.message,
										class_name: 'color success'
									});
								}
								$http.get(BASE_URL + 'products/get_product_categories').then(function (Categories) {
									$scope.category = Categories.data;
								});
							},
							function (response) {
								console.log(response);
							}
						);
					});
			};

			$scope.deleteProduct = function (PRODUCTID) {
				// Appending dialog to document.body to cover sidenav in docs app
				var confirm = $mdDialog.confirm()
					.title('Attention!')
					.textContent('Do you confirm the deletion of all data belonging to this product?')
					.ariaLabel('Delete Product')
					.targetEvent(PRODUCTID)
					.ok('Do it!')
					.cancel('Cancel');

				$mdDialog.show(confirm).then(function () {
					var config = {
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
						}
					};
					$http.post(BASE_URL + 'products/remove/' + PRODUCTID, config)
						.then(
							function (response) {
								if (response.data.success == true) {
									$.gritter.add({
										title: '<b>' + NTFTITLE + '</b>',
										text: response.data.message,
										class_name: 'color success'
									});
								}
								$http.get(BASE_URL + 'api/products').then(function (Products) {
									$scope.products = Products.data;
								});
							},
							function (response) {
								console.log(response);
							}
						);

				}, function () {
				});
			};
		});

		$scope.AddProduct = function () {
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});

			if (!$scope.product) {
				var dataObj = $.param({
					name: '',
					category: '',
					purchaseprice: '',
					saleprice: '',
					code: '',
					tax: '',
					stock: '',
					description: '',
					custom_fields: ''
				});
			} else {
				var dataObj = $.param({
					name: $scope.product.productname,
					categoryid: $scope.product.categoryid,
					purchaseprice: $scope.product.purchase_price,
					saleprice: $scope.product.sale_price,
					code: $scope.product.code,
					tax: $scope.product.vat,
					stock: $scope.product.stock,
					description: $scope.product.description,
					custom_fields: $scope.tempArr
				});
			}
			
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'products/create/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$mdSidenav('Create').close();
							$http.get(BASE_URL + 'api/products').then(function (Products) {
								$scope.products = Products.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};


		$scope.itemsPerPage = 5;
		$scope.currentPage = 0;
		$scope.range = function () {
			var rangeSize = 5;
			var ps = [];
			var start;

			start = $scope.currentPage;
			//  console.log($scope.pageCount(),$scope.currentPage)
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};

		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};

		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};

		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};

		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};

		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};

		$scope.pageCount = function () {
			return Math.ceil($scope.products.length / $scope.itemsPerPage) - 1;
		};
	});
}

function Product_Controller($scope, $http, $mdSidenav, $mdDialog) {
	"use strict";

	$scope.Update = buildToggler('Update');
	$scope.toggleFilter = buildToggler('ContentFilter');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();
		};
	}

	$scope.close = function () {
		$mdSidenav('Update').close();
	};

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'product/' + PRODUCTID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$http.get(BASE_URL + 'api/product/' + PRODUCTID).then(function (Product) {
		$scope.product = Product.data;

		$http.get(BASE_URL + 'products/get_product_categories').then(function (Categories) {
			$scope.category = Categories.data;
		});

		$scope.UpdateProduct = function () {
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});

			if (!$scope.product) {
				var dataObj = $.param({
					name: '',
					category: '',
					purchaseprice: '',
					saleprice: '',
					code: '',
					tax: '',
					stock: '',
					description: '',
					custom_fields: ''
				});
			} else {
				var dataObj = $.param({
					name: $scope.product.productname,
					categoryid: $scope.product.categoryid,
					purchaseprice: $scope.product.purchase_price,
					saleprice: $scope.product.sale_price,
					code: $scope.product.code,
					tax: $scope.product.vat,
					stock: $scope.product.stock,
					description: $scope.product.description,
					custom_fields: $scope.tempArr
				});
			}

			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'products/update/' + PRODUCTID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$mdSidenav('Update').close();
							$http.get(BASE_URL + 'api/product/' + PRODUCTID).then(function (Product) {
								$scope.product = Product.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.Delete = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title('Attention!')
				.textContent('Do you confirm the deletion of all data belonging to this product?')
				.ariaLabel('Delete Product')
				.targetEvent(PRODUCTID)
				.ok('Do it!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'products/remove/' + PRODUCTID, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'products';
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

	});
}

function Settings_Controller($scope, $http, $mdDialog, $mdSidenav, $sce) {
	"use strict";

	$scope.close = function () {
		$mdDialog.hide();
		$mdSidenav('CreateCustomField').close();
		$mdSidenav('FieldDetail').close();
		$mdSidenav('RestoreDatabaseNav').close();
		$mdSidenav('uploadAppFiles').close();
		$mdSidenav('RunMySQL').close();
		$scope.viewPassword = false;
	};

	function buildToggler(navID) {
		$mdSidenav(navID).toggle();
	}

	$scope.uploadAppFiles = function(){
		$mdSidenav('uploadAppFiles').toggle();
	}

	$scope.RunMySQL = function(){
		$mdSidenav('RunMySQL').toggle();
	}

	$http.get(BASE_URL + 'api/languages').then(function (Languages) {
		$scope.languages = Languages.data;
	});

	$http.get(BASE_URL + 'api/currencies').then(function (Currencies) {
		$scope.currencies = Currencies.data;
	});

	$http.get(BASE_URL + 'api/countries').then(function (Countries) {
		$scope.countries = Countries.data;
	});

	$http.get(BASE_URL + 'api/timezones').then(function (Timezones) {
		$scope.timezones = Timezones.data;
	});

	$http.get(BASE_URL + 'api/accounts').then(function (Accounts) {
		$scope.accounts = Accounts.data;
	});

	$scope.systemInfo = function(ev) {
		$mdDialog.show({
			templateUrl: 'system_info.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.executing = false;
	$scope.RunMySQLQuery = function() {
		$scope.executing = true;
		if (!$scope.mysql_query) {
			var dataObj = $.param({
				mysql_query: '',
			});
		} else {
			var dataObj = $.param({
				mysql_query: $scope.mysql_query,
			});
		}
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'settings/execute_mysql_query/', dataObj, config)
			.then(
				function (response) {
					$scope.executing = false;
					if (response.data.success == true) {
						showToast(NTFTITLE, response.data.message, ' success');
						$mdSidenav('RunMySQL').close();
						$scope.mysql_query = '';
					} else {
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.executing = false;
				}
			);
	}

	$http.get(BASE_URL + 'settings/get_payment_gateways').then(function (Data) {
		$scope.payment = Data.data;

		$scope.UpdatePaymentGateway = function(value) {
			$scope.saving = true;

			var dataObj = $.param({
				payment: $scope.payment
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'settings/update_payment_gateway/' + value, dataObj, config)
				.then(
					function (response) {
						$scope.saving = false;
						if (response.data.success == true) {
							$mdDialog.cancel();
							showToast(NTFTITLE, response.data.message, ' success');
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.saving = false;
						console.log(response);
					}
				);
		}
	});

	$scope.paymentGateway = function(gateway) {
			console.log(gateway)
			$mdDialog.show({
				templateUrl: gateway+'.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: gateway
			});
		}

	$http.get(BASE_URL + 'settings/get_backup').then(function (Data) {
		$scope.db_backup = Data.data;
		$scope.BackupDatabase = function (ev) {
			$mdDialog.show({
		      	templateUrl: 'backup.html',
		      	parent: angular.element(document.body),
		      	clickOutsideToClose: false,
		      	fullscreen: false,
		      	escapeToClose: false
		    });
			$http.post(BASE_URL + 'settings/db_backup').then(function (Backup) {
				if (Backup.data.success == true) {
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: Backup.data.message,
						class_name: 'color success'
					});
					$http.get(BASE_URL + 'settings/get_backup').then(function (Data) {
						$scope.db_backup = Data.data;
					});
				} else {
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: Backup.data.message,
						class_name: 'color danger'
					});
				}
				$mdDialog.cancel();
			});
		}
	});

	$scope.RestoreDatabase = function (ev) {
		buildToggler('RestoreDatabaseNav');
		//$mdSidenav('RestoreDatabaseNav').toggle();
	}

	$scope.Restoring = function (id) {
		$mdDialog.show({
			templateUrl: 'restoring.html',
			parent: angular.element(document.body),
			clickOutsideToClose: false,
			fullscreen: false,
			escapeToClose: false
		});
	}

	$scope.RestoreBackup = function(id) {
		var confirm = $mdDialog.confirm()
			.title($scope.lang.restorethisfile)
			.textContent($scope.lang.restorethisfile_msg)
			.ariaLabel('Restore this file')
			.targetEvent(id)
			.ok($scope.lang.restore)
			.cancel($scope.lang.cancel);

		$mdDialog.show(confirm).then(function () {
			$mdDialog.show({
				templateUrl: 'restoring.html',
				parent: angular.element(document.body),
				clickOutsideToClose: false,
				fullscreen: false,
				escapeToClose: false
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'settings/restore_backup/' + id, config)
				.then(
					function (response) {
						console.log(response)
						if (response.data.success == true) {
							window.location.href = BASE_URL + 'login/logout';
						} else {
							$mdDialog.cancel();
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color danger'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);

		}, function () {
		});
	}

	$scope.RemoveBackup = function (id) {
		var confirm = $mdDialog.confirm()
			.title($scope.lang.remove_database_backup)
			.textContent($scope.lang.remove_database_backup_msg)
			.ariaLabel('Delete Custom Field')
			.targetEvent(id)
			.ok($scope.lang.delete)
			.cancel($scope.lang.cancel);

		$mdDialog.show(confirm).then(function () {
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'settings/remove_backup/' + id, config)
				.then(
					function (response) {
						$mdDialog.cancel();
						if (response.data.success == true) {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.message,
								class_name: 'color success'
							});
							$http.get(BASE_URL + 'settings/get_backup').then(function (Data) {
								$scope.db_backup = Data.data;
							});
						} else {
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data.danger,
								class_name: 'color success'
							});
						}
					},
					function (response) {
						console.log(response);
					}
				);

		}, function () {
			//
		});
	}



	$scope.VersionCheck = function (ev) {
	 	$scope.updated = false;
		$mdDialog.show({
			templateUrl: 'version-check-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
		$http.get(BASE_URL + 'settings/version_details').then(function (Version) {
			$scope.versions = Version.data;
			$scope.version_number = Version.data.versions_name;
			$scope.updated = false;
		}, function (error) {
	    	showToast(NTFTITLE, update_error, ' danger');
	    });
	};

	$scope.checkForUpdates = function(ev) {
		$mdDialog.show({
	      	templateUrl: 'checking.html',
	      	parent: angular.element(document.body),
	      	clickOutsideToClose: false,
	      	fullscreen: false,
	      	escapeToClose: false
	    });
		$http.get(BASE_URL + 'settings/version_detail').then(function (Version) {
			$scope.Version_detail = Version.data.settings.versions_name;
			$scope.Version_latest = Version.data.version.version_number; 
			$scope.msg = Version.data.msg;
			$scope.updated = Version.data.updated;
			$scope.version_log = $sce.trustAsHtml(Version.data.list_array_log);
            $scope.changeLog = $sce.trustAsHtml(Version.data.version_changelog);
	      	$mdDialog.cancel();
	     	$mdDialog.show({
	      		templateUrl: 'version-check-template.html',
	      		scope: $scope,
	      		preserveScope: true,
	      		targetEvent: ev
			});
	    }, function (error) {
	    	showToast(NTFTITLE, update_error, ' danger');
	    });
    };

    $scope.downloadUpdate = function() {
    	$mdDialog.show({
	      	templateUrl: 'updating.html',
	      	parent: angular.element(document.body),
	      	clickOutsideToClose: false,
	      	fullscreen: false,
	      	escapeToClose: false
	    });
    };

    $http.get(BASE_URL + 'api/load_config').then(function (Data) {
		$scope.rebrand = Data.data;
		if ($scope.rebrand.enable_support_button_on_client == '1') {
			$scope.rebrand.enable_support_button_on_client = true;
		} else {
			$scope.rebrand.enable_support_button_on_client = false;
		}
	});

    $scope.settings = {};
    $scope.settings.loader = true;
	$http.get(BASE_URL + 'api/settings_detail').then(function (Settings) {
		$scope.settings_detail = Settings.data;
		$scope.settings.loader = false;

		// Send email for testing the email settings
		$scope.sendingTestEmail = false;
		$scope.sendTestEmail = function() {
			$scope.sendingTestEmail = true;
			var dataObj = $.param({
				email: $scope.settings_detail.testEmail
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'settings/sendTestEmail';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.sendingTestEmail = false;
						if (response.data.success) {
							showToast(NTFTITLE, response.data.message, ' success');
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.sendingTestEmail = false;
						showToast(NTFTITLE, email_error, ' danger');
					});
		};


		if ($scope.settings_detail.pushState === '1') {
			$scope.settings_detail.pushState = true;
		} else {
			$scope.settings_detail.pushState = false;
		}
		if ($scope.settings_detail.two_factor_authentication === '1') {
			$scope.settings_detail.two_factor_authentication = true;
		} else {
			$scope.settings_detail.two_factor_authentication = false;
		}
		if ($scope.settings_detail.voicenotification === '1') {
			$scope.settings_detail.voicenotification = true;
		} else {
			$scope.settings_detail.voicenotification = false;
		}
		if ($scope.settings_detail.paypalenable === '1') {
			$scope.settings_detail.paypalenable = true;
		} else {
			$scope.settings_detail.paypalenable = false;
		}
		if ($scope.settings_detail.paypalsandbox === '1') {
			$scope.settings_detail.paypalsandbox = true;
		} else {
			$scope.settings_detail.paypalsandbox = false;
		}
		if ($scope.settings_detail.authorize_enable === '1') {
			$scope.settings_detail.authorize_enable = true;
		} else {
			$scope.settings_detail.authorize_enable = false;
		}
		if ($scope.settings_detail.is_mysql == '1') {
			$scope.settings_detail.is_mysql = true;
		} else {
			$scope.settings_detail.is_mysql = false;
		}
		// var smtppass;
		// if ($scope.settings_detail.smtppassoword == '********') {
		// 	smtppass = 'null';
		// } else {
		// 	smtppass = $scope.settings_detail.smtppassoword;
		// }
		$scope.savingSettings = false;
		$scope.UpdateSettings = function () {
			$scope.savingSettings = true;
			var dataObj = $.param({
				crm_name: $scope.settings_detail.crm_name,
				company: $scope.settings_detail.company,
				email: $scope.settings_detail.email,
				address: $scope.settings_detail.address,
				city: $scope.settings_detail.city,
				town: $scope.settings_detail.town,
				state: $scope.settings_detail.state,
				country_id: $scope.settings_detail.country_id,
				zipcode: $scope.settings_detail.zipcode,
				phone: $scope.settings_detail.phone,
				fax: $scope.settings_detail.fax,
				vatnumber: $scope.settings_detail.vatnumber,
				taxoffice: $scope.settings_detail.taxoffice,
				currencyid: $scope.settings_detail.currencyid,
				termtitle: $scope.settings_detail.termtitle,
				termdescription: $scope.settings_detail.termdescription,
				dateformat: $scope.settings_detail.dateformat,
				languageid: $scope.settings_detail.languageid,
				default_timezone: $scope.settings_detail.default_timezone,
				smtphost: $scope.settings_detail.smtphost,
				smtpport: $scope.settings_detail.smtpport,
				emailcharset: $scope.settings_detail.emailcharset,
				email_encryption: $scope.settings_detail.email_encryption,
				smtpusername: $scope.settings_detail.smtpusername,
				smtppassoword: $scope.settings_detail.smtppassoword,
				sendermail: $scope.settings_detail.sendermail,
				sender_name: $scope.settings_detail.sender_name,
				accepted_files_formats: $scope.settings_detail.accepted_files_formats,
				allowed_ip_adresses: $scope.settings_detail.allowed_ip_adresses,
				pushState: $scope.settings_detail.pushState,
				voicenotification: $scope.settings_detail.voicenotification,
				paypalenable: $scope.settings_detail.paypalenable,
				authorize_enable: $scope.settings_detail.authorize_enable,
				paypalemail: $scope.settings_detail.paypalemail,
				paypalsandbox: $scope.settings_detail.paypalsandbox,
				paypalcurrency: $scope.settings_detail.paypalcurrency,
				authorize_login_id: $scope.settings_detail.authorize_login_id,
				authorize_transaction_key: $scope.settings_detail.authorize_transaction_key,
				two_factor_authentication: $scope.settings_detail.two_factor_authentication,
				authorize_record_account: $scope.settings_detail.authorize_record_account,
				paypal_record_account: $scope.settings_detail.paypal_record_account,
				is_mysql: $scope.settings_detail.is_mysql,

				inv_prefix: $scope.finance.inv_prefix,
				inv_suffix: $scope.finance.inv_suffix,
				project_prefix: $scope.finance.project_prefix,
				project_suffix: $scope.finance.project_suffix,
				order_prefix: $scope.finance.order_prefix,
				order_suffix: $scope.finance.order_suffix,
				expense_suffix: $scope.finance.expense_suffix,
				expense_prefix: $scope.finance.expense_prefix,
				proposal_suffix: $scope.finance.proposal_suffix,
				proposal_prefix: $scope.finance.proposal_prefix,
				tax_label: $scope.finance.tax_label,

				custom_css: $('#custom_css').val(),
				custom_header_js: $('#custom_header_js').val(),
				custom_footer_js: $('#custom_footer_js').val(),
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'settings/update/ciuis';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.savingSettings = false;
						if(response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.savingSettings = false;
						showToast(NTFTITLE, 'Error', ' danger');
					}
				);
		};

		$scope.seePasswordModal = function(ev) {
			$mdDialog.show({
		      	templateUrl: 'see_smtp_password.html',
		      	scope: $scope,
		      	preserveScope: true,
		      	targetEvent: ev
		    });
		}

		$scope.viewPassword = false;
		$scope.viewing = false;
		$scope.viewSMTPPassword = function() {
			$scope.viewing = true;
			var dataObj = $.param({
				password: $scope.your_login_password,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'settings/get_smtp_password';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.viewing = false;
						if(response.data.success == true) {
							$scope.viewPassword = true;
							$scope.final_smtp_password = response.data.password;
						} else {
							$scope.viewPassword = false;
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.viewPassword = false;
						$scope.viewing = false;
						showToast(NTFTITLE, 'Error', ' danger');
					}
				);
		}
	});

	$http.get(BASE_URL + 'api/custom_fields/').then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$http.get(BASE_URL + 'api/get_appconfig/').then(function (Data) {
		$scope.finance = Data.data;
	});

	$scope.CreateCustomField = function() {
		buildToggler('CreateCustomField');
	} 

	$scope.GetFieldDetail = function (id) {
		$http.get(BASE_URL + 'api/custom_field_data_by_id/' + id).then(function (selected_field) {
			$scope.selected_field = selected_field.data;

			$scope.AddOptionToField = function () {
				$scope.selected_field.data.push({
					name: $scope.selected_field.new_option_name,
				});
				for (var i = 0; i < $scope.selected_field.data.length; i++) {
					$scope.selected_field.data[i].id = i;
				}
				$scope.selected_field.new_option_name = null;
			};

			$scope.RemoveFieldOption = function (index) {
				$scope.selected_field.data.splice(index, 1);
			};

			$scope.UpdateCustomField = function () {
				if ($scope.selected_field.type === 'select') {
					$scope.field_data = JSON.stringify($scope.selected_field.data);
				} else {
					$scope.field_data = null;
				}
				var dataObj = $.param({
					name: $scope.selected_field.name,
					type: $scope.selected_field.type,
					order: $scope.selected_field.order,
					data: $scope.field_data,
					relation: $scope.selected_field.relation,
					icon: '',
					permission: $scope.selected_field.permission

				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				var posturl = BASE_URL + 'settings/update_custom_field/' + $scope.selected_field.id;
				$http.post(posturl, dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$mdSidenav('FieldDetail').close();
							$.gritter.add({
								title: '<b>' + NTFTITLE + '</b>',
								text: response.data,
								class_name: 'color success'
							});
						},
						function (response) {
							console.log(response);
						}
					);
			};
		});
	};

	$scope.RemoveCustomField = function (index) {
		var field = $scope.custom_fields[index];
		var confirm = $mdDialog.confirm()
			.title($scope.lang.remove_custom_field)
			.textContent($scope.lang.custom_field_remove_msg)
			.ariaLabel('Delete Custom Field')
			.targetEvent(field.id)
			.ok($scope.lang.delete)
			.cancel($scope.lang.cancel);

		$mdDialog.show(confirm).then(function () {
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'settings/remove_custom_field/' + field.id, config)
				.then(
					function (response) {
						console.log(response);
						$scope.custom_fields.splice(index, 1);
					},
					function (response) {
						console.log(response);
					}
				);

		}, function () {
			//
		});
	};

	$scope.FieldDetail = function() {
		buildToggler('FieldDetail');
	} 

	$scope.select_options = [];

	$scope.new_custom_field = {
		permission: false,
		new_option_name: ''
	};

	$scope.AddOption = function () {
		$scope.select_options.push({
			name: $scope.new_custom_field.new_option_name,
		});
		for (var i = 0; i < $scope.select_options.length; i++) {
			$scope.select_options[i].id = i;
		}
		$scope.new_custom_field.new_option_name = null;
	};

	$scope.RemoveOption = function (index) {
		$scope.select_options.splice(index, 1);
	};

	$scope.AddCustomField = function () {
		if ($scope.new_custom_field.type === 'select') {
			$scope.field_data = JSON.stringify($scope.select_options);
		} else {
			$scope.field_data = null;
		}
		var dataObj = $.param({
			name: $scope.new_custom_field.name,
			type: $scope.new_custom_field.type,
			order: $scope.new_custom_field.order,
			data: $scope.field_data,
			relation: $scope.new_custom_field.relation,
			icon: '',
			permission: $scope.new_custom_field.permission,
			active: 'true'

		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'settings/create_custom_field/';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					console.log(response);
					$mdSidenav('CreateCustomField').close();
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: response.data,
						class_name: 'color success'
					});
					$http.get(BASE_URL + 'api/custom_fields/').then(function (custom_fields) {
						$scope.custom_fields = custom_fields.data;
					});
					$scope.new_custom_field = [];
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.UpdateCustomFieldStatus = function (id, value) {
		$http.post(BASE_URL + 'settings/update_custom_field_status/' + id + '/' + value + '/')
			.then(
				function (response) {
					console.log(response);
					$http.get(BASE_URL + 'api/custom_fields/').then(function (custom_fields) {
						$scope.custom_fields = custom_fields.data;
					});
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.custom_fields_types = [{
		'id': '1',
		'type': 'input',
		'name': 'Input'
	}, {
		'id': '2',
		'type': 'date',
		'name': 'Date Picker'
	}, {
		'id': '3',
		'type': 'textarea',
		'name': 'Text Area'
	}, {
		'id': '4',
		'type': 'select',
		'name': 'Select'
	}];

	$scope.custom_fields_relation_types = [{
		'id': '1',
		'relation': 'invoice',
		'name': 'Invoice'
	}, {
		'id': '2',
		'relation': 'proposal',
		'name': 'Proposal'
	}, {
		'id': '3',
		'relation': 'customer',
		'name': 'Customer'
	}, {
		'id': '4',
		'relation': 'task',
		'name': 'Task'
	}, {
		'id': '5',
		'relation': 'project',
		'name': 'Project'
	}, {
		'id': '6',
		'relation': 'ticket',
		'name': 'Ticket'
	}, {
		'id': '7',
		'relation': 'expense',
		'name': 'Expense'
	}, {
		'id': '8',
		'relation': 'product',
		'name': 'Product'
	}, {
		'id': '9',
		'relation': 'lead',
		'name': 'Lead'
	}];
}

function Staffs_Controller($scope, $http, $mdSidenav, $mdDialog, $filter) {
	"use strict";

	$http.get(BASE_URL + 'api/custom_fields_by_type/' + 'staff').then(function (custom_fields) {
		$scope.all_custom_fields = custom_fields.data;
		$scope.custom_fields = $filter('filter')($scope.all_custom_fields, {
			active: 'true',
		});
	});
	
	$scope.Create = buildToggler('Create');

	$scope.ViewStaff = function (staff_id) {
		window.location.href = BASE_URL + 'staff/staffmember/' + staff_id;
	};

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('Create').close();
		$mdDialog.hide();
	};

	// $scope.changeStaff = function (data, type) {
	// 	if (type == 'admin') {
	// 		$scope.staff.admin = true;
	// 		$scope.staff.staffmember = false;
	// 		$scope.staff.other = false;
	// 	}
	// 	if (type == 'staff') {
	// 		$scope.staff.admin = false;
	// 		$scope.staff.other = false;
	// 		$scope.staff.staffmember = true;
	// 	}
	// 	if (type == 'other') {
	// 		$scope.staff.admin = false;
	// 		$scope.staff.other = true;
	// 		$scope.staff.staffmember = false;
	// 	}
	// }

	$http.get(BASE_URL + 'api/departments').then(function (Departments) {
		$scope.departments = Departments.data;

		$scope.NewDepartment = function () {
			var confirm = $mdDialog.prompt()
				.title('New Department')
				.textContent('Please type department name')
				.placeholder('Department Name')
				.ariaLabel('Department Name')
				.initialValue('')
				.required(true)
				.ok('Add!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'staff/add_department/', dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$scope.departments.push({
								'id': response.data,
								'name': result,
							});
						},
						function (response) {
							console.log(response);
						}
					);
			}, function () {

			});
		};

		$scope.EditDepartment = function (index) {
			var department = $scope.departments[index];
			var confirm = $mdDialog.prompt()
				.title('Update Department')
				.textContent('Please type new department name.')
				.placeholder('Department name')
				.ariaLabel('Department Name')
				.initialValue(department.name)
				.targetEvent(event)
				.required(true)
				.ok('Save')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'staff/update_department/' + department.id, dataObj, config)
					.then(
						function () {
							department.name = result;
						},
						function () {
							//UNSUCCESS
						}
					);
			}, function () {
				//Cancel
			});
		};

		$scope.DeleteDepartment = function (index) {
			var department = $scope.departments[index];
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'staff/remove_department/' + department.id, config)
				.then(
					function (response) {
						$scope.departments.splice($scope.departments.indexOf(department), 1);
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};
	});

	$http.get(BASE_URL + 'api/languages').then(function (Languages) {
		$scope.languages = Languages.data;
	});

	$scope.saving = false;
	$http.get(BASE_URL + 'api/staff/').then(function (Staff) {
		$scope.staff = Staff.data;
		$scope.staff.active = true;
		$scope.staff.admin = true;
		$scope.staff.staffmember = false;
		$scope.staff.type = 'admin';

		$scope.AddStaff = function () {
			$scope.saving = true;
			$scope.type = {};
			if ($scope.staff.type == 'admin') {
				$scope.type.admin = true;
				$scope.type.staffmember = false;
				$scope.type.other = false;
			}
			if ($scope.staff.type == 'staffmember') {
				$scope.type.admin = false;
				$scope.type.staffmember = true;
				$scope.type.other = false;
			}
			if ($scope.staff.type == 'other') {
				$scope.type.admin = false;
				$scope.type.staffmember = false;
				$scope.type.other = true;
			}
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			var dataObj = $.param({
				name: $scope.staff.name,
				email: $scope.staff.email,
				phone: $scope.staff.phone,
				department: $scope.staff.department_id,
				language: $scope.staff.language,
				address: $scope.staff.address,
				admin: $scope.type.admin,
				other: $scope.type.other,
				staffmember: $scope.type.staffmember,
				inactive: $scope.staff.active,
				password: $scope.passwordNew,
				birthday: moment($scope.staff.birthday).format("YYYY-MM-DD"),
				custom_fields: $scope.tempArr,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'staff/create/';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.saving = false;
						if (response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							$mdSidenav('Create').close();
							$http.get(BASE_URL + 'api/staff/').then(function (Staff) {
								$scope.staff = Staff.data;
								$scope.staff.active = true;
								$scope.staff.admin = false;
							});
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.saving = false;
					}
				);
		};
	});
}

function Staff_Controller($scope, $http, $mdSidenav, $mdDialog, $filter) {
	"use strict";

	$scope.Update = buildToggler('Update');
	$scope.Privileges = buildToggler('Privileges');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();
		};
	}

	$scope.close = function () {
		$mdSidenav('Update').close();
		$mdSidenav('Privileges').close();
		$mdDialog.hide();
	};

	$scope.staffLoader = false;

	$scope.ChangeAvatar = function (ev) {
		$mdDialog.show({
			templateUrl: 'change-avatar-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.GoogleCalendar = function (ev) {
		$mdDialog.show({
			templateUrl: 'google-calendar-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$scope.changeStaff = function (data, type) {
		if (type == 'admin') {
			if (data) {
				$scope.staff.staffmember = false;
			} else {
				$scope.staff.staffmember = true;
			}
		}
		if (type == 'staff') {
			if (data) {
				$scope.staff.admin = false;
			} else {
				$scope.staff.admin = true;
			}
		}
	}

	$scope.ChangePasswordAdmin = function (ev) {
		$mdDialog.show({
			templateUrl: 'change-password-admin.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	}

	$scope.UpdatePasswordAdmin = function() {
		$scope.saving = true;
		if (!$scope.apassword) {
			var dataObj = $.param({
				new_password: '',
				c_new_password: ''
			});
		} else {
			var dataObj = $.param({
				new_password: $scope.apassword.newpassword,
				c_new_password: $scope.apassword.c_newpassword
			});
		}
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'staff/changestaffpassword_admin/'+STAFFID, dataObj, config)
			.then(
				function (response) {
					$scope.saving = false;
					if (response.data.success == true) {
						showToast(NTFTITLE, response.data.message, ' success');
						$mdDialog.hide();
					} else {
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.saving = false;
					console.log(response);
				}
			);
	}

	$scope.ChangePassword = function (ev) {
		$mdDialog.show({
			templateUrl: 'change-password.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	}

	$scope.UpdatePassword = function() {
		$scope.saving = true;
		if (!$scope.password) {
			var dataObj = $.param({
				password: '',
				new_password: '',
				c_new_password: ''
			});
		} else {
			var dataObj = $.param({
				password: $scope.password.old,
				new_password: $scope.password.newpassword,
				c_new_password: $scope.password.c_newpassword
			});
		}
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'staff/changestaffpassword/', dataObj, config)
			.then(
				function (response) {
					$scope.saving = false;
					if (response.data.success == true) {
						showToast(NTFTITLE, response.data.message, ' success');
						$mdDialog.hide();
					} else {
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.saving = false;
					console.log(response);
				}
			);
	}

	$scope.UpdateGoogleCalendar = function () {
		if ($scope.staff.google_calendar_enable === true) {
			$scope.Enable = 1;
		} else {
			$scope.Enable = 0;
		}
		var dataObj = $.param({
			google_calendar_id: $scope.staff.google_calendar_id,
			google_calendar_api_key: $scope.staff.google_calendar_api_key,
			google_calendar_enable: $scope.Enable,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'staff/update_google_calendar/' + STAFFID, dataObj, config)
			.then(
				function (response) {
					console.log(response);
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
						text: response.data.message,
						class_name: response.data.color,
					});
					$mdDialog.hide();

				},
				function (response) {
					console.log(response);
				}
			);
	};

	$http.get(BASE_URL + 'api/departments').then(function (Departments) {
		$scope.departments = Departments.data;
	});
	$http.get(BASE_URL + 'api/languages').then(function (Languages) {
		$scope.languages = Languages.data;
	});

	$http.get(BASE_URL + 'api/invoices').then(function (Invoices) {
		$scope.all_invoices = Invoices.data;
		$scope.invoices = $filter('filter')($scope.all_invoices, {
			staff_id: STAFFID,
		});
	});

	$scope.GoInvoice = function (index) {
		var invoice = $scope.invoices[index];
		window.location.href = BASE_URL + 'invoices/invoice/' + invoice.id;
	};

	$http.get(BASE_URL + 'api/proposals').then(function (Proposals) {
		$scope.all_proposals = Proposals.data;
		$scope.proposals = $filter('filter')($scope.all_proposals, {
			assigned: STAFFID,
		});
	});

	$scope.GoProposal = function (index) {
		var proposal = $scope.proposals[index];
		window.location.href = BASE_URL + 'proposals/proposal/' + proposal.id;
	};

	$http.get(BASE_URL + 'api/tickets').then(function (Tickets) {
		$scope.all_tickets = Tickets.data;
		$scope.tickets = $filter('filter')($scope.all_tickets, {
			staff_id: STAFFID,
		});
	});

	$scope.GoTicket = function (index) {
		var ticket = $scope.tickets[index];
		window.location.href = BASE_URL + 'tickets/ticket/' + ticket.id;
	};

	$http.get(BASE_URL + 'api/custom_fields_data_by_type/' + 'staff/' + STAFFID).then(function (custom_fields) {
		$scope.custom_fields = custom_fields.data;
	});

	$http.get(BASE_URL + 'api/staff_detail/' + STAFFID).then(function (StaffDetail) {
		$scope.staff = StaffDetail.data;

		$scope.UpdateWorkPlan = function () {
			var dataObj = $.param({
				work_plan: JSON.stringify($scope.staff.work_plan)
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'staff/update_workplan/' + STAFFID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						console.log(response);
						$.gritter.add({
							title: '<b>' + NTFTITLE + '</b>',
							text: response.data,
							class_name: 'color success'
						});
					},
					function (response) {
						console.log(response);
					}
				);
		};

		$scope.saving = false;
		$scope.UpdateStaff = function () {
			$scope.saving = true;
			$scope.type = {};
			if ($scope.staff.type == 'admin') {
				$scope.type.admin = true;
				$scope.type.staffmember = false;
				$scope.type.other = false;
			}
			if ($scope.staff.type == 'staffmember') {
				$scope.type.admin = false;
				$scope.type.staffmember = true;
				$scope.type.other = false;
			}
			if ($scope.staff.type == 'other') {
				$scope.type.admin = false;
				$scope.type.staffmember = false;
				$scope.type.other = true;
			}
			$scope.tempArr = [];
			angular.forEach($scope.custom_fields, function (value) {
				if (value.type === 'input') {
					$scope.field_data = value.data;
				}
				if (value.type === 'textarea') {
					$scope.field_data = value.data;
				}
				if (value.type === 'date') {
					$scope.field_data = moment(value.data).format("YYYY-MM-DD");
				}
				if (value.type === 'select') {
					$scope.field_data = JSON.stringify(value.selected_opt);
				}
				$scope.tempArr.push({
					id: value.id,
					name: value.name,
					type: value.type,
					order: value.order,
					data: $scope.field_data,
					relation: value.relation,
					permission: value.permission,
				});
			});
			var dataObj = $.param({
				name: $scope.staff.name,
				email: $scope.staff.email,
				phone: $scope.staff.phone,
				department: $scope.staff.department_id,
				language: $scope.staff.language,
				address: $scope.staff.address,
				admin: $scope.type.admin,
				staffmember: $scope.type.staffmember,
				other: $scope.type.other,
				inactive: $scope.staff.active,
				birthday: moment($scope.staff.birthday).format("YYYY-MM-DD"),
				custom_fields: $scope.tempArr,
			});
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			var posturl = BASE_URL + 'staff/update/' + STAFFID;
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.saving = false;
						if (response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							$mdSidenav('Update').close();
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						$scope.saving = false;
						console.log(response);
					}
				);
		};

		$scope.Delete = function () {
			// Appending dialog to document.body to cover sidenav in docs app
			var confirm = $mdDialog.confirm()
				.title('Attention!')
				.textContent('Do you confirm the deletion of all data belonging to this staff?')
				.ariaLabel('Delete Staff')
				.targetEvent(STAFFID)
				.ok('Do it!')
				.cancel('Cancel');

			$mdDialog.show(confirm).then(function () {
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'staff/remove/' + STAFFID, config)
					.then(
						function (response) {
							console.log(response);
							window.location.href = BASE_URL + 'staff';
						},
						function (response) {
							console.log(response);
						}
					);

			}, function () {
				//
			});
		};

		$scope.UpdatePrivileges = function (id, value, privilege_id) {
			$http.post(BASE_URL + 'staff/update_privilege/' + id + '/' + value + '/' + privilege_id)
				.then(
					function (response) {
						console.log(response);
					},
					function (response) {
						console.log(response);
					}
				);
		};

		var canvas = document.getElementById("staff_sales_chart");
		var multiply = {
			beforeDatasetsDraw: function (chart, options, el) {
				chart.ctx.globalCompositeOperation = 'multiply';
			},
			afterDatasetsDraw: function (chart, options) {
				chart.ctx.globalCompositeOperation = 'source-over';
			},
		};
		var gradientThisWeek = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
		gradientThisWeek.addColorStop(0, '#ffbc00');
		gradientThisWeek.addColorStop(1, '#fff');
		var gradientPrevWeek = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
		gradientPrevWeek.addColorStop(0, '#616f8c');
		gradientPrevWeek.addColorStop(1, '#fff');
		var config = {
			type: 'bar',
			data: $scope.staff.properties.chart_data,
			options: {
				elements: {
					point: {
						radius: 0,
						hitRadius: 5,
						hoverRadius: 5
					}
				},
				legend: {
					display: false,
				},
				scales: {
					xAxes: [{
						display: false,
					}],
					yAxes: [{
						display: false,
						ticks: {
							beginAtZero: true,
						},
					}]
				},
				legend: {
					display: true
				}
			},
			plugins: [multiply],
		};
		window.chart = new Chart(canvas, config);
	});
}

function Reports_Controller($scope, $http) {
	"use strict";

	var retrievePath = localStorage.getItem('findPath');
	if (retrievePath) {
		retrievePath = JSON.parse(retrievePath);
	}
	$scope.overview = {};
	$scope.overview.loader = true;

	$http.get(BASE_URL + 'api/stats').then(function (Stats) {
		$scope.stats = Stats.data;
		$scope.overview.loader = false;

		if (retrievePath) {
			if (retrievePath.type == 'report' && retrievePath.view == 'timesheet') {
				$scope.ctrl = {};
				$scope.ctrl.selectedIndex = 4;
				$('#timesheetTab').click();
				$scope.getTimesheet();
				localStorage.clear();
			}
		}

		new Chart($('#invoice_chart_by_status'), {
			type: 'horizontalBar',
			data: $scope.stats.invoice_chart_by_status,
			options: {
				legend: {
					display: false,
				},
				responsive: true
			}
		});

		new Chart($('#leads_to_win_by_leadsource'), {
			type: 'horizontalBar',
			data: $scope.stats.leads_to_win_by_leadsource,
			options: {
				legend: {
					display: false,
				}
			}
		});

		new Chart($('#leads_by_leadsource'), {
			type: 'horizontalBar',
			data: $scope.stats.leads_by_leadsource,
			options: {
				legend: {
					display: false,
				}
			}
		});

		new Chart($('#expensesbycategories'), {
			type: 'bar',
			data: $scope.stats.expenses_by_categories,
			options: {
				legend: {
					display: false,
				}
			}
		});

		new Chart($('#top_selling_staff_chart'), {
			type: 'line',
			data: $scope.stats.top_selling_staff_chart,
			options: {
				legend: {
					display: false,
				}
			}
		});

		var CustomerGraph;
		$.get(BASE_URL + 'report/customer_monthly_increase_chart/' + $scope.CustomerReportMonth, function (response) {
			var ctx = $('#customergraph_ciuis-xe').get(0).getContext('2d');
			CustomerGraph = new Chart(ctx, {
				'type': 'bar',
				data: response,
				options: {
					responsive: true
				},
			});
		}, 'json');

		$scope.CustomerMonthChanged = function () {
			lead_graph.destroy();
			$.get(BASE_URL + 'report/customer_monthly_increase_chart/' + $scope.CustomerReportMonth, function (response) {
				var ctx = $('#customergraph_ciuis-xe').get(0).getContext('2d');
				CustomerGraph = new Chart(ctx, {
					'type': 'bar',
					data: response,
					options: {
						responsive: true
					},
				});
			}, 'json');
		};

		var lead_graph;
		$.get(BASE_URL + 'report/lead_graph/' + $scope.LeadReportMonth, function (response) {
			var ctx = $('#lead_graph').get(0).getContext('2d');
			lead_graph = new Chart(ctx, {
				'type': 'bar',
				data: response,
				options: {
					responsive: true
				},
			});
		}, 'json');


		$scope.LeadMonthChanged = function () {
			lead_graph.destroy();
			$.get(BASE_URL + 'report/lead_graph/' + $scope.LeadReportMonth, function (response) {
				var ctx = $('#lead_graph').get(0).getContext('2d');
				lead_graph = new Chart(ctx, {
					'type': 'bar',
					data: response,
					options: {
						responsive: true
					},
				});
			}, 'json');
		};

		var expenses_payments_graph;
		$.get(BASE_URL + 'report/expenses_payments_graph/' + $scope.paymentsExpensesByYear, function (response) {
			var ctx = $('#incomingsvsoutgoins').get(0).getContext('2d');
			expenses_payments_graph = new Chart(ctx, {
				'type': 'line',
				data: response,
				options: {
					responsive: true
				},
			});
		}, 'json');


		$scope.getPaymentsExpensesByYear = function () {
			expenses_payments_graph.destroy();
			$.get(BASE_URL + 'report/expenses_payments_graph/' + $scope.paymentsExpensesByYear, function (response) {
				var ctx = $('#incomingsvsoutgoins').get(0).getContext('2d');
				expenses_payments_graph = new Chart(ctx, {
					'type': 'line',
					data: response,
					options: {
						responsive: true
					},
				});
			}, 'json');
		};
	});

	$http.get(BASE_URL + 'report/get_reports_data').then(function (response) {
		$scope.report = response.data;

		Highcharts.chart('incomingsvsoutgoins_weekly', {
			chart: {
				type: 'column'
			},
			title: {
				text: ''
			},
			height: 380,
			colors: ['#5ba768','#e26862'],
			xAxis: {
				categories: $scope.report.weekdays,
				title: {
					text: null
				}
			},
			pointRange: 86400000,
			yAxis: {
				min: 0,
				title: {
					text: '',
					align: 'high'
				},
				labels: {
					overflow: 'justify'
				}
			},
			tooltip: {
				valueSuffix: ''
			},
			plotOptions: {
				bar: {
					dataLabels: {
						enabled: true
					}
				}
			},
			legend: {
				layout: 'horixontal',
				align: 'right',
				verticalAlign: 'top',
				floating: true,
				borderWidth: 1,
				backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
				shadow: true
			},
			credits: {
				enabled: false
			},
			options: {
				responsive: true
			},
			series: [
				{name: lang.payments,data: $scope.report.payments}, 
				{name: lang.expenses,data: $scope.report.expenses}, 
			]
		});

	});

	$scope.getTimesheet = function() {
		$scope.timesheet = {};
		$scope.timesheet.loader = true;
		$http.get(BASE_URL + 'report/get_timesheet_data').then(function (response) {
			$scope.timesheets = response.data.timesheet;
			$scope.total_time = response.data.total;
			$scope.timesheet.loader = false;

			$scope.itemsPerPage = 5;
			$scope.currentPage = 0;
			$scope.range = function () {
				var rangeSize = 5;
				var ps = [];
				var start;

				start = $scope.currentPage;
				if (start > $scope.pageCount() - rangeSize) {
					start = $scope.pageCount() - rangeSize + 1;
				}

				for (var i = start; i < start + rangeSize; i++) {
					if (i >= 0) {
						ps.push(i);
					}
				}
				return ps;
			};

			$scope.prevPage = function () {
				if ($scope.currentPage > 0) {
					$scope.currentPage--;
				}
			};

			$scope.DisablePrevPage = function () {
				return $scope.currentPage === 0 ? "disabled" : "";
			};

			$scope.nextPage = function () {
				if ($scope.currentPage < $scope.pageCount()) {
					$scope.currentPage++;
				}
			};

			$scope.DisableNextPage = function () {
				return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
			};

			$scope.setPage = function (n) {
				$scope.currentPage = n;
			};

			$scope.pageCount = function () {
				return Math.ceil($scope.timesheets.length / $scope.itemsPerPage) - 1;
			};
		});
	}
}

function Calendar_Controller($scope, $http, $mdDialog, $filter) {
	"use strict";

	$scope.close = function () {
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'api/events').then(function (Events) {
		$scope.all_events = Events.data;
		$scope.today_events = $filter('filter')($scope.all_events, {
			date: $scope.curDate,
		});
	});


	$http.get(BASE_URL + 'api/appointments').then(function (appointments) {
		$scope.all_appointments = appointments.data;
		$scope.today_appointments = $filter('filter')($scope.all_appointments, {
			date: $scope.curDate,
			status: 1,
		});
	});

	$http.get(BASE_URL + 'api/appointments').then(function (appointments) {
		$scope.all_appointments = appointments.data;
		$scope.requested_appointments = $filter('filter')($scope.all_appointments, {
			status: 0,
		});
	});

	$http.get(BASE_URL + 'api/appointments').then(function (appointments) {
		$scope.appointments = appointments.data;
	});

	$scope.ShowAppointment = function (index) {
		var appointment = $scope.appointments[index];
		$mdDialog.show({
			contentElement: '#Appointment-' + appointment.id,
			parent: angular.element(document.body),
			targetEvent: index,
			clickOutsideToClose: true
		});
	};

	$scope.ConfirmAppointment = function (id) {
		var posturl = BASE_URL + 'appointments/confirm_appointment/' + id;
		$http.post(posturl)
			.then(
				function (response) {
					console.log(response);
					$mdDialog.hide();
				},
				function (response) {
					console.log(response);
				}
			);
	};
	$scope.DeclineAppointment = function (id) {
		var posturl = BASE_URL + 'appointments/decline_appointment/' + id;
		$http.post(posturl)
			.then(
				function (response) {
					console.log(response);
					$mdDialog.hide();
				},
				function (response) {
					console.log(response);
				}
			);
	};
	$scope.MarkAsDoneAppointment = function (id) {
		var posturl = BASE_URL + 'appointments/mark_as_done_appointment/' + id;
		$http.post(posturl)
			.then(
				function (response) {
					console.log(response);
					$mdDialog.hide();
				},
				function (response) {
					console.log(response);
				}
			);
	};

}

function Appointments_Controller($scope, $http, $mdDialog, $filter, $timeout) {
	"use strict";

	$scope.events = [];
	$scope.navigatorConfig = {
		selectMode: "day",
		showMonths: 3,
		skipMonths: 3,
		onTimeRangeSelected: function (args) {
			$scope.weekConfig.startDate = args.day;
			$scope.dayConfig.startDate = args.day;
			loadEvents();
		}
	};
	$scope.dayConfig = {
		viewType: "Day",
		onTimeRangeSelected: function (args) {
			var params = {
				start: args.start.toString(),
				end: args.end.toString(),
				text: "New event"
			};

			$http.post("backend_create.php", params).success(function (data) {
				$scope.events.push({
					start: args.start,
					end: args.end,
					text: "New event",
					id: data.id
				});
			});
		},
		onEventMove: function (args) {
			var params = {
				id: args.e.id(),
				newStart: args.newStart.toString(),
				newEnd: args.newEnd.toString()
			};

			$http.post("backend_move.php", params);
		},
		onEventResize: function (args) {
			var params = {
				id: args.e.id(),
				newStart: args.newStart.toString(),
				newEnd: args.newEnd.toString()
			};

			$http.post("backend_move.php", params);
		},
		onEventClick: function (args) {
			$mdDialog.show({
				contentElement: '#Appointment-' + args.e.id(),
				parent: angular.element(document.body),
				targetEvent: args.e.id(),
				clickOutsideToClose: true
			});
		}
	};
	$scope.weekConfig = {
		visible: false,
		viewType: "Week",
		onTimeRangeSelected: function (args) {
			var params = {
				start: args.start.toString(),
				end: args.end.toString(),
				text: "New event"
			};

			$http.post("backend_create.php", params).success(function (data) {
				$scope.events.push({
					start: args.start,
					end: args.end,
					text: "New event",
					id: data.id
				});
			});
		},
		onEventMove: function (args) {
			var params = {
				id: args.e.id(),
				newStart: args.newStart.toString(),
				newEnd: args.newEnd.toString()
			};

			$http.post("backend_move.php", params);
		},
		onEventResize: function (args) {
			var params = {
				id: args.e.id(),
				newStart: args.newStart.toString(),
				newEnd: args.newEnd.toString()
			};

			$http.post("backend_move.php", params);
		},
		onEventClick: function (args) {
			$mdDialog.show({
				contentElement: '#Appointment-' + args.e.id(),
				parent: angular.element(document.body),
				targetEvent: args.e.id(),
				clickOutsideToClose: true
			});
		}
	};
	$scope.showDay = function () {
		$scope.dayConfig.visible = true;
		$scope.weekConfig.visible = false;
		$scope.navigatorConfig.selectMode = "day";
	};
	$scope.showWeek = function () {
		$scope.dayConfig.visible = false;
		$scope.weekConfig.visible = true;
		$scope.navigatorConfig.selectMode = "week";
	};
	loadEvents();

	function loadEvents() {
		// using $timeout to make sure all changes are applied before reading visibleStart() and visibleEnd()
		$timeout(function () {
			var params = {
				start: $scope.week.visibleStart().toString(),
				end: $scope.week.visibleEnd().toString()
			};
			$http.get(BASE_URL + 'api/all_appointments').then(function (appointments) {
				$scope.events = appointments.data;
			});
		});
	}

	$scope.close = function () {
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'api/appointments').then(function (appointments) {
		$scope.all_appointments = appointments.data;
		$scope.today_appointments = $filter('filter')($scope.all_appointments, {
			date: $scope.curDate,
			status: 1,
		});
	});

	$http.get(BASE_URL + 'api/appointments').then(function (appointments) {
		$scope.all_appointments = appointments.data;
		$scope.requested_appointments = $filter('filter')($scope.all_appointments, {
			status: 0,
		});
	});

	$http.get(BASE_URL + 'api/appointments').then(function (appointments) {
		$scope.appointments = appointments.data;
	});

	$scope.ShowAppointment = function (id) {
		$mdDialog.show({
			contentElement: '#Appointment-' + id,
			parent: angular.element(document.body),
			targetEvent: id,
			clickOutsideToClose: true
		});
	};

	$scope.ConfirmAppointment = function (id) {
		var posturl = BASE_URL + 'appointments/confirm_appointment/' + id;
		$http.post(posturl)
			.then(
				function (response) {
					console.log(response);
					$mdDialog.hide();
				},
				function (response) {
					console.log(response);
				}
			);
	};
	$scope.DeclineAppointment = function (id) {
		var posturl = BASE_URL + 'appointments/decline_appointment/' + id;
		$http.post(posturl)
			.then(
				function (response) {
					console.log(response);
					$mdDialog.hide();
				},
				function (response) {
					console.log(response);
				}
			);
	};
	$scope.MarkAsDoneAppointment = function (id) {
		var posturl = BASE_URL + 'appointments/mark_as_done_appointment/' + id;
		$http.post(posturl)
			.then(
				function (response) {
					console.log(response);
					$mdDialog.hide();
				},
				function (response) {
					console.log(response);
				}
			);
	};

}

function Chart_Controller($scope, $http) {
	"use strict";

	$http.get(BASE_URL + 'api/stats').then(function (Stats) {
		$scope.stats = Stats.data;

		Highcharts.setOptions({
			colors: ['#ffbc00', 'rgb(239, 89, 80)']
		});

		Highcharts.chart('monthlyexpenses', {
			title: {
				text: '',
			},
			credits: {
				enabled: false
			},
			chart: {
				backgroundColor: 'transparent',
				marginBottom: 0,
				marginLeft: -10,
				marginRight: -10,
				marginTop: 0,
				type: 'area',
			},
			exporting: {
				enabled: false
			},
			plotOptions: {
				series: {
					fillOpacity: 0.1
				},
				area: {
					lineWidth: 1,
					marker: {
						lineWidth: 2,
						symbol: 'circle',
						fillColor: 'black',
						radius: 3,
					},
					legend: {
						radius: 2,
					}
				}
			},
			xAxis: {
				categories: $scope.stats.months,
				visible: true,
			},
			yAxis: {
				title: {
					enabled: false
				},
				visible: false
			},
			tooltip: {
				shadow: false,
				useHTML: true,
				padding: 0,
				formatter: function () {
					return '<div class="bis-tooltip" style="background-color: ' + this.color + '">' + this.x + ' <span>' + this.y + ' ' + $scope.cur_symbol + '</span></div>'
				}
			},
			legend: {
				align: 'right',
				enabled: false,
				verticalAlign: 'top',
				layout: 'vertical',
				x: -15,
				y: 100,
				itemMarginBottom: 20,
				useHTML: true,
				labelFormatter: function () {
					return '<span style="color:' + this.color + '">' + this.name + '</span>'
				},
				symbolPadding: 0,
				symbolWidth: 0,
				symbolRadius: 0
			},
			series: [{
				"data": $scope.stats.monthly_expenses,
			}]
		}, function (chart) {
			var series = chart.series;
			series.forEach(function (serie) {
				if (serie.legendSymbol) {
					serie.legendSymbol.destroy();
				}
				if (serie.legendLine) {
					serie.legendLine.destroy();
				}
			});
		});

		var MainChartOptions = {
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				xAxes: [{
					categoryPercentage: .2,
					barPercentage: 1,
					position: 'top',
					gridLines: {
						color: '#C7CBD5',
						zeroLineColor: '#C7CBD5',
						drawTicks: true,
						borderDash: [8, 5],
						offsetGridLines: false,
						tickMarkLength: 10,
						callback: function (value) {

						}
					},
					ticks: {
						callback: function (value) {
							return value.charAt(0) + value.charAt(1) + value.charAt(2);
						}
					}
				}],
				yAxes: [{
					display: false,
					gridLines: {
						drawBorder: false,
						drawOnChartArea: false,
						borderDash: [8, 5],
						offsetGridLines: false
					},
					ticks: {
						beginAtZero: true,
						maxTicksLimit: 5,
					}
				}]
			},
			legend: {
				display: false
			}
		};
		var ctx = $('#main-chart');
		var mainChart = new Chart(ctx, {
			type: 'bar',
			data: $scope.stats.weekly_sales_chart,
			borderRadius: 10,
			options: MainChartOptions
		});
	});

}

function Emails_Controller($scope, $http, $mdSidenav, $mdDialog, $filter, $sce) {
	'use strict';

	$scope.close = function () {
		$mdDialog.hide();
	};

	$scope.template = {};
	$scope.template.loader = true;
	$scope.template.loadEmails = true;
	$http.get(BASE_URL + 'emails/get_email_templates').then(function (Templates) {
		$scope.templates = Templates.data;
		$scope.template.loader = false;
	});

	$http.get(BASE_URL + 'emails/get_emails').then(function (Emails) {
		$scope.emails = Emails.data;
		$scope.template.loadEmails = false;

		$scope.viewEmail = function (index) {
			$scope.email = $scope.emails[index];
			$mdDialog.show({
				templateUrl: 'emailDialog.html',
				scope: $scope,
				preserveScope: true,
				targetEvent: $scope.email.id
			});
			$scope.email.message = $sce.trustAsHtml($scope.email.message);
		};

		$scope.itemsPerPage = 10;
			$scope.currentPage = 0;
			$scope.range = function () {
			var rangeSize = 10;
			var ps = [];
			var start;

			start = $scope.currentPage;
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};

		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};

		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};

		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};

		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};

		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};

		$scope.pageCount = function () {
			return Math.ceil($scope.emails.length / $scope.itemsPerPage) - 1;
		};
	})
}

function Email_Controller($scope, $http, $mdSidenav, $mdDialog, $filter) {
	'use strict';

	$scope.template = {};
	$scope.template_loader = true;
	$http.get(BASE_URL + 'emails/get_email_template/'+TEMPLATEID).then(function (Template) {
		$scope.template = Template.data;
		$scope.template_loader = false;
		tinyMCE.activeEditor.setContent($scope.template.message, {format : 'raw'});
	});
	$http.get(BASE_URL + 'emails/template_fields/'+TEMPLATEID).then(function (Data) {
		$scope.template_fields = Data.data;
	});
	//tinyMCE.activeEditor.dom.addClass(tinyMCE.activeEditor.dom, 'myclass');

	$scope.saving = false;
	$scope.UpdateTemplate = function() {
		$scope.saving = true;
		var status, attachment;
		if ($scope.template.status == true) {
			status = 1;
		} else {
			status = 0;
		}
		if ($scope.template.attachment == true) {
			attachment = 1;
		} else {
			attachment = 0;
		}
		var data = tinyMCE.activeEditor.getContent({format : 'raw'});
		var dataObj = $.param({
			subject: $scope.template.subject,
			from_name: $scope.template.from_name,
			message: data.replace(/&nbsp;/g, ' ').replace(/;/g, '').replace(/&nbsp/g, ' '),
			status: status,
			attachment: attachment
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'emails/update_template/' + TEMPLATEID;
		$http.post(posturl, dataObj, config)
		.then(
			function (response) {
				if (response.data.success == true) {
					showToast(NTFTITLE, response.data.message, 'success');
				}
				$scope.saving = false;
			}, function(){
				$scope.saving = false;
			}
			);
	}
	
}

function Search_Controller($scope, $http, $mdSidenav, $mdDialog, $filter) {
	"use strict";
	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();
		};
	}

	$scope.close = function () {
		$mdSidenav('searchNav').close();
	};

	$scope.searchNav = function() {
		buildToggler('searchNav');
	}

}

function Login_Controller() {
	"use strict";
}

function WebLeads_Controller($scope, $http, $mdSidenav, $mdDialog, $filter) {
	"use strict";

	$scope.close = function () {
		$mdSidenav('Create').close();
	};

	$scope.createForm = function() {
		$mdSidenav('Create').toggle();
	}

	$scope.webleadsLoader = true;
	$http.get(BASE_URL + 'leads/webleads').then(function (WebLeads) {
		$scope.webleads = WebLeads.data;
		$scope.webleadsLoader = false;
		console.log($scope.webleads)

		$scope.itemsPerPage = 5;
			$scope.currentPage = 0;
			$scope.range = function () {
			var rangeSize = 5;
			var ps = [];
			var start;

			start = $scope.currentPage;
			if (start > $scope.pageCount() - rangeSize) {
				start = $scope.pageCount() - rangeSize + 1;
			}

			for (var i = start; i < start + rangeSize; i++) {
				if (i >= 0) {
					ps.push(i);
				}
			}
			return ps;
		};

		$scope.prevPage = function () {
			if ($scope.currentPage > 0) {
				$scope.currentPage--;
			}
		};

		$scope.DisablePrevPage = function () {
			return $scope.currentPage === 0 ? "disabled" : "";
		};

		$scope.nextPage = function () {
			if ($scope.currentPage < $scope.pageCount()) {
				$scope.currentPage++;
			}
		};

		$scope.DisableNextPage = function () {
			return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
		};

		$scope.setPage = function (n) {
			$scope.currentPage = n;
		};

		$scope.pageCount = function () {
			return Math.ceil($scope.webleads.length / $scope.itemsPerPage) - 1;
		};
	});

	$http.get(BASE_URL + 'api/leadstatuses').then(function (LeadStatuses) {
		$scope.leadstatuses = LeadStatuses.data;
		$scope.NewStatus = function () {
			var confirm = $mdDialog.prompt()
				.title($scope.lang.new_status)
				.textContent($scope.lang.type_status_name)
				.placeholder($scope.lang.status_name)
				.ariaLabel($scope.lang.status_name)
				.initialValue('')
				.required(true)
				.ok($scope.lang.add)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'leads/add_status/', dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$scope.leadstatuses.push({
								'id': response.data,
								'name': result,
							});
						},
						function (response) {
							console.log(response);
						}
					);
			}, function () {

			});
		};
	});

	$http.get(BASE_URL + 'api/leadsources').then(function (LeadSources) {
		$scope.leadssources = LeadSources.data;
		$scope.NewSource = function () {
			var confirm = $mdDialog.prompt()
				.title($scope.lang.new_source)
				.textContent($scope.lang.type_source_name)
				.placeholder($scope.lang.source_name)
				.ariaLabel($scope.lang.source_name)
				.initialValue('')
				.required(true)
				.ok($scope.lang.add)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'leads/add_source/', dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$scope.leadssources.push({
								'id': response.data,
								'name': result,
							});
						},
						function (response) {
							console.log(response);
						}
					);
			}, function () {

			});
		};
	});

	$scope.AddWebLeadForm = function() {
		$scope.addingLead = true;
		if (!$scope.weblead) {
			var dataObj = $.param({
				name: '',
				assigned_id: '',
				status_id: '',
				source_id: '',
				submit_text: '',
				success_message: '',
				duplicate: '',
				notification: '',
				status: '',
			});
		} else {
			var dataObj = $.param({
				name: $scope.weblead.name,
				assigned_id: $scope.weblead.assigned_id,
				status_id: $scope.weblead.status_id,
				source_id: $scope.weblead.source_id,
				submit_text: $scope.weblead.submit_text,
				success_message: $scope.weblead.success_message,
				duplicate: $scope.weblead.duplicate,
				notification: $scope.weblead.notification,
				status: $scope.weblead.status,
			}); 
		}
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'leads/add_weblead_form/', dataObj, config).then(function (response) {
			$scope.addingLead = false;
			if (response.data.success == true) {
				showToast(NTFTITLE, response.data.message, ' success');
				$mdSidenav('Create').close();
				window.location.href = BASE_URL+'leads/form/'+response.data.id;
			} else {
				showToast(NTFTITLE, response.data.message, ' danger');
			}
		}, function(response) {
			$scope.addingLead = false;
		});
	}
}

function Consultant_Controller($scope, $http, $mdSidenav, $mdDialog, $filter) {
	"use strict";
	$http.get(BASE_URL + 'api/get_consultant_data').then(function (Data) {
		$scope.consultant = Data.data;

			Highcharts.chart('consultantchart', {
			chart: {
				type: 'area'
			},
			credits: {
        		enabled: false
    		},
			title: {
				text: $scope.consultant.lang.sales_vs_expenses,
				color: '#777',
				//font-weight: 'bold'
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				//startOnTick: true,
				endOnTick: true,
				min: 0.5,
				tickColor: '#fff',
				categories: $scope.consultant.months_short
			},
			yAxis: {
				title: {
					text: $scope.consultant.lang.amount
				}
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true
					},
					enableMouseTracking: false
				}
			},
			series: [{
				name: $scope.consultant.lang.expenses,
				data: $scope.consultant.monthly_expenses,
				color: '#f52f24'
				},{
				name: $scope.consultant.lang.sales,
				data: $scope.consultant.monthly_sales,
				backgroundColor:'rgba(255, 255, 255, 0.1)',
				color: '#26c281'
			}],
		});

		Highcharts.setOptions({
			colors: ['#ffbc00', 'rgb(239, 89, 80)']
		});

			Highcharts.chart('monthlyexpenses', {
			title: {
				text: '',
			},
			credits: {
				enabled: false
			},
			chart: {
				backgroundColor: 'transparent',
				marginBottom: 0,
				marginLeft: -10,
				marginRight: -10,
				marginTop: 0,
				type: 'area',
			},
			exporting: {
				enabled: false
			},
			plotOptions: {
				series: {
					fillOpacity: 0.1
				},
				area: {
					lineWidth: 1,
					marker: {
						lineWidth: 2,
						symbol: 'circle',
						fillColor: 'black',
						radius: 3,
					},
					legend: {
						radius: 2,
					}
				}
			},
			xAxis: {
				categories: $scope.consultant.months,
				visible: true,
			},
			yAxis: {
				title: {
					enabled: false
				},
				visible: false
			},
			tooltip: {
				shadow: false,
				useHTML: true,
				padding: 0,
				formatter: function () {
					return '<div class="bis-tooltip" style="background-color: ' + this.color + '">' + this.x + ' <span>' + this.y + ' ' + $scope.cur_symbol + '</span></div>'
				}
			},
			legend: {
				align: 'right',
				enabled: false,
				verticalAlign: 'top',
				layout: 'vertical',
				x: -15,
				y: 100,
				itemMarginBottom: 20,
				useHTML: true,
				labelFormatter: function () {
					return '<span style="color:' + this.color + '">' + this.name + '</span>'
				},
				symbolPadding: 0,
				symbolWidth: 0,
				symbolRadius: 0
			},
			series: [{
				"data": $scope.consultant.monthly_expenses,
			}]
		}, function (chart) {
			var series = chart.series;
			series.forEach(function (serie) {
				if (serie.legendSymbol) {
					serie.legendSymbol.destroy();
				}
				if (serie.legendLine) {
					serie.legendLine.destroy();
				}
			});
		});
	});

}

function WebLead_Controller($scope, $http, $mdSidenav, $mdDialog, $filter) {
	"use strict";

	$scope.close = function () {
		$mdSidenav('Create').close();
	};

	$scope.createForm = function() {
		$mdSidenav('Create').toggle();
	}

	$http.get(BASE_URL + 'api/leadstatuses').then(function (LeadStatuses) {
		$scope.leadstatuses = LeadStatuses.data;
		$scope.NewStatus = function () {
			var confirm = $mdDialog.prompt()
				.title($scope.lang.new_status)
				.textContent($scope.lang.type_status_name)
				.placeholder($scope.lang.status_name)
				.ariaLabel($scope.lang.status_name)
				.initialValue('')
				.required(true)
				.ok($scope.lang.add)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'leads/add_status/', dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$scope.leadstatuses.push({
								'id': response.data,
								'name': result,
							});
						},
						function (response) {
							console.log(response);
						}
					);
			}, function () {

			});
		};
	});

	$http.get(BASE_URL + 'api/leadsources').then(function (LeadSources) {
		$scope.leadssources = LeadSources.data;
		$scope.NewSource = function () {
			var confirm = $mdDialog.prompt()
				.title($scope.lang.new_source)
				.textContent($scope.lang.type_source_name)
				.placeholder($scope.lang.source_name)
				.ariaLabel($scope.lang.source_name)
				.initialValue('')
				.required(true)
				.ok($scope.lang.add)
				.cancel($scope.lang.cancel);

			$mdDialog.show(confirm).then(function (result) {
				var dataObj = $.param({
					name: result,
				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				$http.post(BASE_URL + 'leads/add_source/', dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$scope.leadssources.push({
								'id': response.data,
								'name': result,
							});
						},
						function (response) {
							console.log(response);
						}
					);
			}, function () {

			});
		};
	});

	$http.get(BASE_URL + 'api/countries').then(function (Countries) {
		$scope.countries = Countries.data; 
	});

	$scope.webleadsLoader = true;
	$http.get(BASE_URL + 'leads/get_weblead/'+FORMID).then(function (WebLeads) {
		$scope.weblead = WebLeads.data;
		$scope.webleadsLoader = false;
		var data = '<iframe width="600" height="800" src="'+BASE_URL + 'forms/wlf/'+$scope.weblead.token+'" frameborder="0" allowfullscreen></iframe>';
		$('.editorField').text(data);
		var formData;
		$scope.form = {
          components: JSON.parse($scope.weblead.data),
          display: 'form'
        };
		Formio.builder(document.getElementById('builder'), $scope.form, {
			builder: {
				basic: false,
				advanced: false,
				data: false,
				layout: false,
				customBasic: false,
				custom: {
					title: lang.formbuilder,
					default: true,
					weight: 10,
					components: {
						title: {
							title: lang.title,
							key: 'l_title',
							icon: 'ion-bookmark',
							default: 'Mr',
							placeholder: lang.title,
							schema: {
								label: lang.title,
								type: 'textfield',
								key: 'l_title',
								input: false
							}
						},
						name: {
							title: lang.name,
							key: 'l_name',
							icon: 'ion-android-contact',
							placeholder: lang.name,
							schema: {
								label: lang.name,
								type: 'textfield',
								key: 'l_name',
								input: true
							}
						},
						company: {
							title: lang.company,
							key: 'l_company',
							icon: 'ion-ios-briefcase',
							placeholder: lang.company,
							schema: {
								label: lang.company,
								type: 'textfield',
								key: 'l_company',
								input: true
							}
						},
						phone: {
							title: lang.phone,
							key: 'l_phone',
							icon: 'ion-ios-telephone',
							placeholder: lang.phone,
							schema: {
								label: lang.phone,
								type: 'number',
								key: 'l_phone',
								input: true
							}
						},
						email: {
							title: lang.email,
							key: 'l_email',
							icon: 'ion-at',
							placeholder: lang.email,
							schema: {
								label: lang.email,
								type: 'email',
								key: 'l_email',
								input: true
							}
						},
						website: {
							title: lang.website,
							key: 'l_website',
							icon: 'ion-earth',
							placeholder: lang.website,
							schema: {
								label: lang.website,
								type: 'textfield',
								key: 'l_website',
								input: true
							}
						},
						country: {
							title: lang.country,
							key: 'l_country',
							icon: 'ion-ios-flag',
							placeholder: lang.country,
							data: {
								json: [
								{"value":"a","label":"A"},
								{"value":"b","label":"B"},
								{"value":"c","label":"C"},
								{"value":"d","label":"D"}
								]
							},
							schema: {
								label: lang.country,
								type: 'textfield',
								key: 'l_country',
								input: true,
							}
						},
						state: {
							title: lang.state,
							key: 'l_state',
							icon: 'ion-map',
							placeholder: lang.state,
							schema: {
								label: lang.state,
								type: 'textfield',
								key: 'l_state',
								input: true
							}
						},
						city: {
							title: lang.city,
							key: 'l_city',
							icon: 'ion-map',
							placeholder: lang.city,
							schema: {
								label: lang.city,
								type: 'textfield',
								key: 'l_city',
								input: true
							}
						},
						zip: {
							title: lang.zip,
							key: 'l_zip',
							icon: 'ion-ios-paperplane-outline',
							placeholder: lang.zip,
							schema: {
								label: lang.zip,
								type: 'number',
								key: 'l_zip',
								input: true
							}
						},
						address: {
							title: lang.address,
							key: 'l_address',
							icon: 'ion-ios-location-outline',
							placeholder: lang.address,
							schema: {
								label: lang.address,
								type: 'textfield',
								key: 'l_address',
								input: true
							}
						},
						description: {
							title: lang.description,
							key: 'l_description',
							icon: 'ion-ios-list-outline',
							placeholder: lang.description,
							schema: {
								label: lang.description,
								type: 'textarea',
								key: 'l_description',
								input: true,
							}
						},
						date: {
							title: lang.date,
							key: 'l_date',
							icon: 'ion-ios-calendar-outline',
							placeholder: lang.date,
							schema: {
								label: lang.date,
								type: 'datetime',
								key: 'l_date',
								input: true
							}
						},
						submit: {
							title: lang.submit,
							key: 'l_submit',
							icon: '',
							placeholder: lang.submit,
							schema: {
								label: lang.submit,
								type: 'button',
								action: 'submit',
								key: 'l_submit',
								input: true
							}
						}
					}
				},
				// layout: {
				//   components: {
				//     fieldset: false,
				//     well: false,
				//     tabs: false,
				//     panel: false,
				//     table: false
				//   }
				// }
				},
				editForm: {
					textfield: [{key: 'api',ignore: true},{key: 'data',ignore: true},{key: 'conditional',ignore: true},{key: 'logic',ignore: true}],
					email: [{key: 'api',ignore: true},{key: 'data',ignore: true},{key: 'conditional',ignore: true},{key: 'logic',ignore: true}],
					textarea: [{key: 'api',ignore: true},{key: 'data',ignore: true},{key: 'conditional',ignore: true},{key: 'logic',ignore: true}],
					number: [{key: 'api',ignore: true},{key: 'data',ignore: true},{key: 'conditional',ignore: true},{key: 'logic',ignore: true}],
					button: [{key: 'api',ignore: true},{key: 'data',ignore: true},{key: 'conditional',ignore: true},{key: 'logic',ignore: true}],
					datetime: [{key: 'api',ignore: true},{key: 'time',ignore: true},{key: 'data',ignore: true},{key: 'conditional',ignore: true},
						{key: 'logic',ignore: true}],
					select: [{key: 'api',ignore: true},{key: 'data',ignore: true},{key: 'conditional',ignore: true},{key: 'logic',ignore: true}],
				}
			}).then(function(builder) {
			checkBuilderComponents(builder.schema.components);
			// builder.on('saveComponent', function() {
			//   console.log(builder.schema);
			// });
			builder.on('change', function() {
				checkBuilderComponents(builder.schema.components);
			});
			$('#saveFormContent').on('click', function() {
				var hasError = false;
				if (builder.schema.components.length > 0) {
					for (var i = 0; i < builder.schema.components.length; i++) {
						var component = builder.schema.components[i];
						hasError = true;
						if (component.key == "eMail") {
							hasError = false;
							break;
						}
					}
				}
				if(hasError) {
					showToast(NTFTITLE, lang.emailError, ' danger');
				}
				if (!hasError) {
					$scope.savingLead = true;
					var dataObj = $.param({
						components: JSON.stringify(builder.schema.components)
					});
					var config = {
						headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}
					};
					$http.post(BASE_URL + 'leads/save_weblead_components/'+FORMID, dataObj, config)
						.then(function (response) {
							$scope.savingLead = false;
							if (response.data.success == true) {
								showToast(NTFTITLE, response.data.message, ' success');
							} else {
								showToast(NTFTITLE, response.data.message, ' danger');
							}
					}, function(response) {
						$scope.savingLead = false;
					});
				}
			});
		});
	});

	function checkBuilderComponents(data) {
		$('#builder-l_title').css('display', 'block');
		$('#builder-l_name').css('display', 'block');
		$('#builder-l_company').css('display', 'block');
		$('#builder-l_phone').css('display', 'block');
		$('#builder-l_email').css('display', 'block');
		$('#builder-l_website').css('display', 'block');
		$('#builder-l_country').css('display', 'block');
		$('#builder-l_state').css('display', 'block');
		$('#builder-l_city').css('display', 'block');
		$('#builder-l_zip').css('display', 'block');
		$('#builder-l_address').css('display', 'block');
		$('#builder-l_description').css('display', 'block');
		$('#builder-l_date').css('display', 'block');
		$('#builder-l_submit').css('display', 'block');
		for (var i = 0; i < data.length; i++) {
			if (data[i].key == 'title') {
				$('#builder-l_title').css('display', 'none');
			}
			if (data[i].key == "name") {
				$('#builder-l_name').css('display', 'none');
			}
			if (data[i].key == "companyName") {
				$('#builder-l_company').css('display', 'none');
			}
			if (data[i].key == "phone") {
				$('#builder-l_phone').css('display', 'none');
			}
			if (data[i].key == "eMail") {
				$('#builder-l_email').css('display', 'none');
			}
			if (data[i].key == "webSite") {
				$('#builder-l_website').css('display', 'none');
			}
			if (data[i].key == "country") {
				$('#builder-l_country').css('display', 'none');
			}
			if (data[i].key == "state") {
				$('#builder-l_state').css('display', 'none');
			}
			if (data[i].key == "city") {
				$('#builder-l_city').css('display', 'none');
			}
			if (data[i].key == "zipCode") {
				$('#builder-l_zip').css('display', 'none');
			}
			if (data[i].key == "address") {
				$('#builder-l_address').css('display', 'none');
			}
			if (data[i].key == "description") {
				$('#builder-l_description').css('display', 'none');
			}
			if (data[i].key == "date") {
				$('#builder-l_date').css('display', 'none');
			}
			if (data[i].key == "submit") {
				$('#builder-l_submit').css('display', 'none');
			}
		}
		
	}

	$scope.SaveWebLeadForm = function() {
		$scope.savingLead = true;
		if (!$scope.weblead) {
			var dataObj = $.param({
				name: '',
				assigned_id: '',
				status_id: '',
				source_id: '',
				submit_text: '',
				success_message: '',
				duplicate: '',
				notification: '',
				status: '',
			});
		} else {
			var dataObj = $.param({
				name: $scope.weblead.name,
				assigned_id: $scope.weblead.assigned_id,
				status_id: $scope.weblead.status_id,
				source_id: $scope.weblead.source_id,
				submit_text: $scope.weblead.submit_text,
				success_message: $scope.weblead.success_message,
				duplicate: $scope.weblead.duplicate,
				notification: $scope.weblead.notification,
				status: $scope.weblead.status,
			}); 
		}
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'leads/save_weblead_form/'+FORMID, dataObj, config).then(function (response) {
			$scope.savingLead = false;
			if (response.data.success == true) {
				showToast(NTFTITLE, response.data.message, ' success');
			} else {
				showToast(NTFTITLE, response.data.message, ' danger');
			}
		}, function(response) {
			$scope.savingLead = false;
		});
	}

	$scope.changeStatus = function(status) {
		var dataObj = $.param({
			status: $scope.weblead.status,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		$http.post(BASE_URL + 'leads/change_weblead_status/'+FORMID, dataObj, config).then(function (response) {
			if (response.data.success == true) {
				showToast(NTFTITLE, response.data.message, ' success');
			} else {
				showToast(NTFTITLE, response.data.message, ' danger');
			}
		}, function(response) {
		});
	}

	$scope.deleteForm = function() {
		var confirm = $mdDialog.confirm()
			.title($scope.lang.delete)
			.textContent($scope.lang.delete_web_form)
			.ariaLabel($scope.lang.delete)
			.targetEvent(FORMID)
			.ok($scope.lang.delete)
			.cancel($scope.lang.cancel);

		$mdDialog.show(confirm).then(function () {
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'leads/delete_web_form/' + FORMID, config)
				.then(
					function (response) {
						if (response.data.success == true) {
							window.location.href = BASE_URL+'leads/forms';
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
						console.log(response);
					}
				);
		}, function () {
				//
		});
	}
}

function Timesheets_Controller($scope, $http, $mdSidenav, $mdDialog, $filter, $sce) {
	'use strict';

	$scope.close = function () {
		$mdDialog.hide();
		$http.get(BASE_URL + 'timesheets/get_timesheet_data').then(function (response) {
			$scope.timesheets = response.data.timesheet;
		});
	};

	$scope.loadingTimesheets = true;

	$scope.LogTime = function (ev) {
		$mdDialog.show({
			templateUrl: 'add-timer.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$http.get(BASE_URL + 'api/get_open_tasks').then(function (Tasks) {
		$scope.timerTasks = Tasks.data;
	});

	$scope.adding = false;

	$scope.timesheet = {};
	$scope.timesheet.loader = true;

	function getTimesheets() {
		$http.get(BASE_URL + 'timesheets/get_timesheet_data').then(function (response) {
			$scope.timesheets = response.data.timesheet;
			$scope.total_time = response.data.total;
			$scope.timesheet.loader = false;
			$scope.refreshing = false;
			$scope.loadingTimesheets = false;

			$scope.itemsPerPage = 8;
			$scope.currentPage = 0;
			$scope.range = function () {
				var rangeSize = 8;
				var ps = [];
				var start;

				start = $scope.currentPage;
				if (start > $scope.pageCount() - rangeSize) {
					start = $scope.pageCount() - rangeSize + 1;
				}

				for (var i = start; i < start + rangeSize; i++) {
					if (i >= 0) {
						ps.push(i);
					}
				}
				return ps;
			};

			$scope.prevPage = function () {
				if ($scope.currentPage > 0) {
					$scope.currentPage--;
				}
			};

			$scope.DisablePrevPage = function () {
				return $scope.currentPage === 0 ? "disabled" : "";
			};

			$scope.nextPage = function () {
				if ($scope.currentPage < $scope.pageCount()) {
					$scope.currentPage++;
				}
			};

			$scope.DisableNextPage = function () {
				return $scope.currentPage === $scope.pageCount() ? "disabled" : "";
			};

			$scope.setPage = function (n) {
				$scope.currentPage = n;
			};

			$scope.pageCount = function () {
				return Math.ceil($scope.timesheets.length / $scope.itemsPerPage) - 1;
			};

			$scope.viewTimesheet = function(index) {
				for (var i = 0; i < $scope.timesheets.length; i++) {
					if ($scope.timesheets[i].id == index) {
						$scope.loggedtime = $scope.timesheets[i];
						continue;
					}
				}
				if ($scope.loggedtime) {
					$mdDialog.show({
						templateUrl: 'view_timesheet.html',
						scope: $scope,
						preserveScope: true,
						targetEvent: $scope.loggedtime.id
					});
				}
			}

			$scope.editTimeLog = function(index) {
				for (var i = 0; i < $scope.timesheets.length; i++) {
					if ($scope.timesheets[i].id == index) {
						$scope.updatetimer = $scope.timesheets[i];
						continue;
					}
				}
				if ($scope.updatetimer) {
					$mdDialog.show({
						templateUrl: 'update_timer.html',
						scope: $scope,
						preserveScope: true,
						targetEvent: $scope.updatetimer.id
					});
				}
			}
		});
	}

	getTimesheets();

	$scope.refreshing = false;
	$scope.refreshTimeLogs = function() {
		$scope.refreshing = true;
		getTimesheets();
	}

	$scope.CreateLogTime = function() {
		$scope.adding = true;
		if (!$scope.logtime) {
			var dataObj = $.param({
				task: '',
				start_time: '',
				end_time: '',
				note: '',
			});
		} else {
			if ($scope.logtime.start_time) {
				$scope.logtime.start_time = moment($scope.logtime.start_time).format("YYYY-MM-DD HH:mm:ss")
			}
			if ($scope.logtime.end_time) {
				$scope.logtime.end_time = moment($scope.logtime.end_time).format("YYYY-MM-DD HH:mm:ss")
			}
			var dataObj = $.param({
				task: $scope.logtime.task,
				start_time: $scope.logtime.start_time,
				end_time: $scope.logtime.end_time,
				note: $scope.logtime.description,
			});
		}
			
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'timesheets/logtime/';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$scope.adding = false;
					if (response.data.success == true) {
						showToast(NTFTITLE, response.data.message, ' success');
						$mdDialog.hide();
						getTimesheets();
					} else {
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.adding = false;
					showToast(NTFTITLE, 'Error', ' danger');
				}
			);
	}

	$scope.saving = false;
	$scope.UpdateLogTime = function(id) {
		$scope.saving = true;
		if (!$scope.updatetimer) {
			var dataObj = $.param({
				task: '',
				start_time: '',
				end_time: '',
				note: '',
			});
		} else {
			if ($scope.updatetimer.start_time) {
				$scope.updatetimer.start_time = moment($scope.updatetimer.start_time).format("YYYY-MM-DD HH:mm:ss")
			}
			if ($scope.updatetimer.end_time) {
				$scope.updatetimer.end_time = moment($scope.updatetimer.end_time).format("YYYY-MM-DD HH:mm:ss")
			}
			var dataObj = $.param({
				task: $scope.updatetimer.task_id,
				start_time: $scope.updatetimer.start_time,
				end_time: $scope.updatetimer.end_time,
				note: $scope.updatetimer.note,
			});
		}
			
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'timesheets/update_logtime/'+id;
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					$scope.saving = false;
					if (response.data.success == true) {
						showToast(NTFTITLE, response.data.message, ' success');
						$mdDialog.hide();
						getTimesheets();
						$scope.getTimer();
					} else {
						showToast(NTFTITLE, response.data.message, ' danger');
					}
				},
				function (response) {
					$scope.saving = false;
					showToast(NTFTITLE, 'Error', ' danger');
				}
			);
	}

	$scope.deleteTimesheet = function(id) {
		var confirm = $mdDialog.confirm()
			.title(langs.delete_timelog)
			.textContent(langs.delete_timelog_message)
			.ariaLabel(langs.delete_timelog)
			.targetEvent(id)
			.ok(langs.delete)
			.cancel(langs.cancel);

		$mdDialog.show(confirm).then(function () {
			var config = {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
				}
			};
			$http.post(BASE_URL + 'timesheets/delete_log/' + id, config)
				.then(
					function (response) {
						if(response.data.success == true) {
							showToast(NTFTITLE, response.data.message, ' success');
							getTimesheets();
							$scope.getTimer();
						} else {
							showToast(NTFTITLE, response.data.message, ' danger');
						}
					},
					function (response) {
					}
				);

		}, function() {
					//
		});
	};
}

CiuisCRM.controller('Ciuis_Controller', Ciuis_Controller);
CiuisCRM.controller('Leads_Controller', Leads_Controller);
CiuisCRM.controller('Lead_Controller', Lead_Controller);
CiuisCRM.controller('Accounts_Controller', Accounts_Controller);
CiuisCRM.controller('Account_Controller', Account_Controller);
CiuisCRM.controller('Customers_Controller', Customers_Controller);
CiuisCRM.controller('Customer_Controller', Customer_Controller);
CiuisCRM.controller('Tasks_Controller', Tasks_Controller);
CiuisCRM.controller('Task_Controller', Task_Controller);
CiuisCRM.controller('Expenses_Controller', Expenses_Controller);
CiuisCRM.controller('Expense_Controller', Expense_Controller);
CiuisCRM.controller('Invoices_Controller', Invoices_Controller);
CiuisCRM.controller('Invoice_Controller', Invoice_Controller);
CiuisCRM.controller('Proposals_Controller', Proposals_Controller);
CiuisCRM.controller('Proposal_Controller', Proposal_Controller);
CiuisCRM.controller('Orders_Controller', Orders_Controller);
CiuisCRM.controller('Order_Controller', Order_Controller);
CiuisCRM.controller('Projects_Controller', Projects_Controller);
CiuisCRM.controller('Project_Controller', Project_Controller);
CiuisCRM.controller('Tickets_Controller', Tickets_Controller);
CiuisCRM.controller('Ticket_Controller', Ticket_Controller);
CiuisCRM.controller('Products_Controller', Products_Controller);
CiuisCRM.controller('Product_Controller', Product_Controller);
CiuisCRM.controller('Settings_Controller', Settings_Controller);
CiuisCRM.controller('Staffs_Controller', Staffs_Controller);
CiuisCRM.controller('Staff_Controller', Staff_Controller);
CiuisCRM.controller('Reports_Controller', Reports_Controller);
CiuisCRM.controller('Calendar_Controller', Calendar_Controller);
CiuisCRM.controller('Appointments_Controller', Appointments_Controller);
CiuisCRM.controller('Chart_Controller', Chart_Controller);
CiuisCRM.controller('Login_Controller', Login_Controller);
CiuisCRM.controller('Search_Controller', Search_Controller);
CiuisCRM.controller('Emails_Controller', Emails_Controller);
CiuisCRM.controller('Email_Controller', Email_Controller);
CiuisCRM.controller('WebLeads_Controller', WebLeads_Controller);
CiuisCRM.controller('WebLead_Controller', WebLead_Controller);
CiuisCRM.controller('Consultant_Controller', Consultant_Controller);
CiuisCRM.controller('Timesheets_Controller', Timesheets_Controller);

// ALL FILTERS

CiuisCRM.filter('trustAsHtml', ['$sce', function ($sce) {
	"use strict";

	return function (text) {
		return $sce.trustAsHtml(text);
	};
}]);

CiuisCRM.filter('pagination', function () {
	"use strict";

	return function (input, start) {
		if (!input || !input.length) {
			return;
		}
		start = +start; //parse to int
		return input.slice(start);
	};
});

CiuisCRM.filter('time', function () {
	"use strict";

	var conversions = {
		'ss': angular.identity,
		'mm': function (value) {
			return value * 60;
		},
		'hh': function (value) {
			return value * 3600;
		}
	};

	var padding = function (value, length) {
		var zeroes = length - ('' + (value)).length,
			pad = '';
		while (zeroes-- > 0) pad += '0';
		return pad + value;
	};

	return function (value, unit, format, isPadded) {
		var totalSeconds = conversions[unit || 'ss'](value),
			hh = Math.floor(totalSeconds / 3600),
			mm = Math.floor((totalSeconds % 3600) / 60),
			ss = totalSeconds % 60;

		format = format || 'hh:mm:ss';
		isPadded = angular.isDefined(isPadded) ? isPadded : true;
		hh = isPadded ? padding(hh, 2) : hh;
		mm = isPadded ? padding(mm, 2) : mm;
		ss = isPadded ? padding(ss, 2) : ss;

		return format.replace(/hh/, hh).replace(/mm/, mm).replace(/ss/, ss);
	};
});

// ALL DIRECTIVES

CiuisCRM.directive('loadMore', function () {
	"use strict";

	return {
		template: "<a ng-click='loadMore()' id='loadButton' class='activity_tumu'><i style='font-size:22px;' class='icon ion-android-arrow-down'></i></a>",
		link: function (scope) {
			scope.LogLimit = 2;
			scope.loadMore = function () {
				scope.LogLimit += 5;
				if (scope.logs.length < scope.LogLimit) {
					CiuisCRM.element(loadButton).fadeOut();
				}
			};
		}
	};
});

CiuisCRM.directive("bindExpression", function ($parse) {
	"use strict";
	var directive = {};
	directive.restrict = 'E';
	directive.require = 'ngModel';
	directive.link = function (scope, element, attrs, ngModel) {
		scope.$watch(attrs.expression, function (newValue) {
			ngModel.$setViewValue(newValue);
		});
		ngModel.$render = function () {
			$parse(attrs.expression).assign(ngModel.viewValue);
		};
	};
	return directive;
});

CiuisCRM.directive('onErrorSrc', function () {
	"use strict";

	return {
		link: function (scope, element, attrs) {
			element.bind('error', function () {
				if (attrs.src !== attrs.onErrorSrc) {
					attrs.$set('src', attrs.onErrorSrc);
				}
			});
		}
	};
});

CiuisCRM.directive('ciuisReady', function () {
	"use strict";
	return {
		link: function ($scope) {
			setTimeout(function () {
				$scope.$apply(function () {
					var milestone_projectExpandablemilestonetitle = $('.milestone_project-action.is-expandable .milestonetitle');
					$(milestone_projectExpandablemilestonetitle).attr('tabindex', '0');
					// Give milestone_projects ID's
					$('.milestone_project').each(function (i, $milestone_project) {
						var $milestone_projectActions = $($milestone_project).find('.milestone_project-action.is-expandable');
						$($milestone_projectActions).each(function (j, $milestone_projectAction) {
							var $milestoneContent = $($milestone_projectAction).find('.content');
							$($milestoneContent).attr('id', 'milestone_project-' + i + '-milestone-content-' + j).attr('role', 'region');
							$($milestoneContent).attr('aria-expanded', $($milestone_projectAction).hasClass('expanded'));
							$($milestone_projectAction).find('.milestonetitle').attr('aria-controls', 'milestone_project-' + i + '-milestone-content-' + j);
						});
					});
					$(milestone_projectExpandablemilestonetitle).click(function () {
						$(this).parent().toggleClass('is-expanded');
						$(this).siblings('.content').attr('aria-expanded', $(this).parent().hasClass('is-expanded'));
					});
					// Expand or navigate back and forth between sections
					$(milestone_projectExpandablemilestonetitle).keyup(function (e) {
						if (e.which === 13) { //Enter key pressed
							$(this).click();
						} else if (e.which === 37 || e.which === 38) { // Left or Up
							$(this).closest('.milestone_project-milestone').prev('.milestone_project-milestone').find('.milestone_project-action .milestonetitle').focus();
						} else if (e.which === 39 || e.which === 40) { // Right or Down
							$(this).closest('.milestone_project-milestone').next('.milestone_project-milestone').find('.milestone_project-action .milestonetitle').focus();
						}
					});
				});
			}, 5000);
			angular.element(document).ready(function () {
				$('.transform_logo').addClass('animated rotateIn'); // Logo Transform
				$('#chooseFile').bind('change', function () {
					var filename = $("#chooseFile").val();
					if (/^\s*$/.test(filename)) {
						$(".file-upload").removeClass('active');
						$("#noFile").text("None Chosen");
					} else {
						$(".file-upload").addClass('active');
						$("#noFile").text(filename.replace("C:\\fakepath\\", ""));
					}
				});
				var $btns = $('.pbtn').click(function () {
					if (this.id == 'all') {
						$('#ciuisprojectcard > div').fadeIn(450);
					} else {
						var $el = $('.' + this.id).fadeIn(450);
						$('#ciuisprojectcard > div').not($el).hide();
					}
					$btns.removeClass('active');
					$(this).addClass('active');
				});

				$('.add-file-cover').hide();

				$(document).on('click', function (e) {
					if ($(e.target).closest('.add-file').length) {
						$(".add-file-cover").show();
					} else if (!$(e.target).closest('.add-file-cover').length) {
						$('.add-file-cover').hide();
					}
				});
				$('.form-field-file').each(function () {
					var label = $('label', this);
					var labelValue = $(label).html();
					var fileInput = $('input[type="file"]', this);
					$(fileInput).on('change', function () {
						var fileName = $(this).val().split('\\').pop();
						if (fileName) {
							$(label).html(fileName);
						} else {
							$(label).html(labelValue);
						}
					});
				});
				$(document).ready(function () {
					$('input[name=type]').change(function () {
						if (!$(this).is(':checked')) {
							return;
						}
						if ($(this).val() === '0') {
							$('.bank').hide();
						} else if ($(this).val() === '1') {
							$('.bank').show();
						}
					});
				});
				$('#ciuisloader').hide();
			});
		}
	};
});
CiuisCRM.directive("strToTime", function () {
	"use strict";
	return {
		require: 'ngModel',
		link: function (scope, element, attrs, ngModelController) {
			ngModelController.$parsers.push(function (data) {
				if (!data) {
					return "";
				}
				return ("0" + data.getHours().toString()).slice(-2) + ":" + ("0" + data.getMinutes().toString()).slice(-2);
			});
			ngModelController.$formatters.push(function (data) {
				if (!data) {
					return null;
				}
				var d = new Date(1970, 1, 1);
				var splitted = data.split(":");
				d.setHours(splitted[0]);
				d.setMinutes(splitted[1]);
				return d;
			});
		}
	};
});
CiuisCRM.directive('ciuisSidebar', function () {
	"use strict";
	return {
		templateUrl: "ciuis-sidebar.html"
	};
});
CiuisCRM.directive('customFieldsVertical', function () {
	"use strict";
	return {
		templateUrl: "custom-fields.html"
	};
});
CiuisCRM.directive("uiDraggable", [
	'$parse',
	'$rootScope',
	function ($parse, $rootScope) {
		"use strict";
		return function (scope, element, attrs) {
			if ($.jQuery && !$.jQuery.event.props.dataTransfer) {
				$.jQuery.event.props.push('dataTransfer');
			}
			element.attr("draggable", false);
			attrs.$observe("uiDraggable", function (newValue) {
				element.attr("draggable", newValue);
			});
			var dragData = "";
			scope.$watch(attrs.drag, function (newValue) {
				dragData = newValue;
			});
			element.bind("dragstart", function (e) {
				var sendData = angular.toJson(dragData);
				var sendChannel = attrs.dragChannel || "defaultchannel";
				e.dataTransfer.setData("Text", sendData);
				$rootScope.$broadcast("ANGULAR_DRAG_START", sendChannel);

			});

			element.bind("dragend", function (e) {
				var sendChannel = attrs.dragChannel || "defaultchannel";
				$rootScope.$broadcast("ANGULAR_DRAG_END", sendChannel);
				if (e.dataTransfer && e.dataTransfer.dropEffect !== "none") {
					if (attrs.onDropSuccess) {
						var fn = $parse(attrs.onDropSuccess);
						scope.$apply(function () {
							fn(scope, {
								$event: e
							});
						});
					}
				}
			});


		};
	}
]);
CiuisCRM.directive("uiOnDrop", [
	'$parse',
	'$rootScope',
	function ($parse, $rootScope) {
		"use strict";
		return function (scope, element, attr) {
			var dropChannel = "defaultchannel";
			var dragChannel = "";
			var dragEnterClass = attr.dragEnterClass || "on-drag-enter";
			var dragHoverClass = attr.dragHoverClass || "on-drag-hover";

			function onDragOver(e) {

				if (e.preventDefault) {
					e.preventDefault(); // Necessary. Allows us to drop.
				}

				if (e.stopPropagation) {
					e.stopPropagation();
				}
				e.dataTransfer.dropEffect = 'move';
				return false;
			}

			function onDragEnter(e) {
				$rootScope.$broadcast("ANGULAR_HOVER", dropChannel);
				element.addClass(dragHoverClass);
			}

			function onDrop(e) {
				if (e.preventDefault) {
					e.preventDefault(); // Necessary. Allows us to drop.
				}
				if (e.stopPropagation) {
					e.stopPropagation(); // Necessary. Allows us to drop.
				}
				var data = e.dataTransfer.getData("Text");
				data = angular.fromJson(data);
				var fn = $parse(attr.uiOnDrop);
				scope.$apply(function () {
					fn(scope, {
						$data: data,
						$event: e
					});
				});
				element.removeClass(dragEnterClass);
			}


			$rootScope.$on("ANGULAR_DRAG_START", function (event, channel) {
				dragChannel = channel;
				if (dropChannel === channel) {

					element.bind("dragover", onDragOver);
					element.bind("dragenter", onDragEnter);

					element.bind("drop", onDrop);
					element.addClass(dragEnterClass);
				}

			});

			$rootScope.$on("ANGULAR_DRAG_END", function (e, channel) {
				dragChannel = "";
				if (dropChannel === channel) {

					element.unbind("dragover", onDragOver);
					element.unbind("dragenter", onDragEnter);

					element.unbind("drop", onDrop);
					element.removeClass(dragHoverClass);
					element.removeClass(dragEnterClass);
				}
			});

			$rootScope.$on("ANGULAR_HOVER", function (e, channel) {
				if (dropChannel === channel) {
					element.removeClass(dragHoverClass);
				}
			});

			attr.$observe('dropChannel', function (value) {
				if (value) {
					dropChannel = value;
				}
			});


		};
	}
]);

function showToast(title, message, type) {
	$.gritter.add({
		title: '<b>' + title + '</b>',
		text: message,
		class_name: 'color '+type,
	});
}