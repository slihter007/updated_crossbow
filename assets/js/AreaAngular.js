var CiuisCRM = angular.module('Ciuis', ['Ciuis.datepicker', 'ngMaterial', 'ngMaterialDatePicker', 'currencyFormat']);

function Area_Controller($scope, $http, $mdSidenav, $filter, dateFilter) {
	"use strict";

	$scope.date = new Date();
	$scope.appurl = BASE_URL;
	$scope.UPIMGURL = UPIMGURL;
	$scope.IMAGESURL = IMAGESURL;
	$scope.SETFILEURL = SETFILEURL;
	$scope.ONLYADMIN = SHOW_ONLY_ADMIN;
	$scope.activestaff = ACTIVESTAFF;
	$scope.cur_symbol = CURRENCY;
	$scope.cur_code = CURRENCY;
	$scope.cur_lct = LOCATE_SELECTED;
	$scope.is_admin = ISADMIN;
	$scope.active_staff_name = ACTIVE_CONTACT_NAME;

	$scope.Notifications = buildToggler('Notifications');
	$scope.Appointment = buildToggler('Appointment');
	$scope.Profile = buildToggler('Profile');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('Notifications').close();
		$mdSidenav('Appointment').close();
		$mdSidenav('Profile').close();
	};
	
	$scope.SelectedAppointmentTime = function(time){
		$scope.AppointmentTime = time;
	};

	$http.get(BASE_URL + 'area/api/get_settings').then(function (Settings) {
		$scope.settings = Settings.data;
	});

	$http.get(BASE_URL + 'area/api/get_projects').then(function (Projects) {
		$scope.projects = Projects.data;
	});

	// $http.get(BASE_URL + 'area/api/get_staff').then(function (Staff) {
	// 	$scope.staff = Staff.data;
	// });

	$http.get(BASE_URL + 'area/api/get_staff').then(function (Staff) {
		$scope.staff = Staff.data;
		$scope.all_staff = Staff.data;
		$scope.available_staff = $filter('filter')($scope.all_staff, {
			appointment_availability: 1,
		});
	});

	$scope.OpenMenu = function () { 
		$('#mobile-menu').show();
	};

	$scope.close = function () {
		$('#mobile-menu').hide();
	};

	$scope.SelectedAppointmentStaff = function (staff) {
		$scope.AppointmentStaff = staff.id;
	};
	
	$scope.ResetAppointment = function (){
		$scope.AppointmentStaff = null;
		$scope.day = null;
		$scope.Available_Times = null;
		$scope.AppointmentTime = null;
	};

	var AppointmentStaff = $scope.AppointmentStaff;

	$scope.CheckTimes = function (day) {
		$http.get(BASE_URL + 'area/api/get_available_times/' + $scope.AppointmentStaff + '/' + day).then(function (Times) {
			$scope.Available_Times = Times.data;
		});
	};
	
	$scope.ConfirmAppointment = function (day) {
		var dataObj = $.param({
			staff_id: $scope.AppointmentStaff,
			booking_date: day,
			start_time: $scope.AppointmentTime,
		});
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'area/appointments/new_appointment';
		$http.post(posturl, dataObj, config)
			.then(
				function (response) {
					console.log(response);
					$mdSidenav('Appointment').close();
					$.gritter.add({
						title: '<b>' + NTFTITLE + '</b>',
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

	$http.get(BASE_URL + 'area/api/get_stats').then(function (Stats) {
		$scope.stats = Stats.data;
		new Chart($('#customer_annual_sales_chart'), {
			type: 'bar',
			data: $scope.stats.chart_data,
			options: {
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
							borderDash: [5, 5],
							offsetGridLines: false,
							tickMarkLength: 10,
							callback: function (value) {
								console.log(value)
									// return value.charAt(0) + value.charAt(1) + value.charAt(2);
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
							drawBorder: true,
							drawOnChartArea: true,
							borderDash: [8, 5],
							offsetGridLines: true
						},
						ticks: {
							beginAtZero: true,
							maxTicksLimit: 12,
						}
					}]
				},
				legend: {
					display: false
				}
			}
		});
	});

	$http.get(BASE_URL + 'area/api/get_notifications').then(function (Notifications) {
		$scope.notifications = Notifications.data; 
	});

	$http.get(BASE_URL + 'area/api/get_logs_by_customerId').then(function (Logs) {
		$scope.logs = Logs.data;
	});

	$http.get(BASE_URL + 'area/api/get_contacts').then(function (Contacts) {
		$scope.all_contacts = Contacts.data;
	});

	$scope.NotificationRead = function (index) {
		var notification = $scope.notifications[index];
		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			}
		};
		var posturl = BASE_URL + 'area/api/mark_read_notification/' + notification.id;
		$http.post(posturl, config) 
			.then(
				function (response) {
					window.location.href = notification.target;
				},
				function (response) {
					console.log(response);
				}
			);
	};

	$scope.appurl = BASE_URL;
	$scope.UPIMGURL = UPIMGURL;
	$scope.IMAGESURL = IMAGESURL;
	$scope.SETFILEURL = SETFILEURL;


	$http.get(BASE_URL + 'area/api/get_leftmenu').then(function (LeftMenu) {
		$scope.all_menu_item = LeftMenu.data;
		$scope.areamenu = $filter('filter')($scope.all_menu_item, {
			show: 'true',
			show_staff: '0',
		});
	});
}

function Project_Controller($scope, $http, $filter, $mdDialog, $sce) {
	"use strict";

	$scope.close = function () {
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'area/api/get_projectdetail/' + PROJECTID).then(function (Project) {
		$scope.project = Project.data;
		$scope.projectmembers = $scope.project.members;
	});
	$http.get(BASE_URL + 'area/api/get_projecttasks/' + PROJECTID).then(function (ProjectTasks) {
		$scope.projecttasks = ProjectTasks.data;
	});

	$http.get(BASE_URL + 'area/api/get_projectmilestones/' + PROJECTID).then(function (Milestones) {
		$scope.milestones = Milestones.data;
	});

	$http.get(BASE_URL + 'area/api/get_notes/project/' + PROJECTID).then(function (Notes) {
		$scope.notes = Notes.data;
	});

	$scope.UploadFile = function (ev) {
		$mdDialog.show({
			templateUrl: 'addfile-template.html',
			scope: $scope,
			preserveScope: true,
			targetEvent: ev
		});
	};

	$http.get(BASE_URL + 'area/api/get_expenses_by_relation/project/' + PROJECTID).then(function (Expenses) {
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

	$scope.addNote = false;
	$http.get(BASE_URL + 'area/api/notes/project/' + PROJECTID).then(function (Notes) {
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
			var posturl = BASE_URL + 'area/projects/addnote';
			$http.post(posturl, dataObj, config)
				.then(
					function (response) {
						$scope.addNote = false;
						if (response.data.success == true) {
							$('.note-description').val('');
							$scope.note = '';
							$http.get(BASE_URL + 'area/api/notes/project/' + PROJECTID).then(function (Notes) {
								$scope.notes = Notes.data;
							});
						} else {
						}
					},
					function (response) {
						$scope.addNote = false;
					}
				);
		};
	});

	$scope.projectFiles = true;
	$http.get(BASE_URL + 'area/api/projectfiles/' + PROJECTID).then(function (Files) {
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
	});

	$http.get(BASE_URL + 'area/api/get_projecttimelogs/' + PROJECTID).then(function (TimeLogs) {
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
	// $http.get(BASE_URL + 'area/api/get_projectfiles/' + PROJECTID).then(function (Files) {
	// 	$scope.files = Files.data;
	// });
}

function Invoices_Controller($scope, $http) {
	"use strict";
	$http.get(BASE_URL + 'area/api/get_invoices').then(function (Invoices) {
		$scope.invoices = Invoices.data;
		$scope.search = {
			customer: ''
		};
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

function Invoice_Controller($scope, $http, $mdSidenav, $mdDialog) {
	"use strict";
	
	$scope.PayViaPaypal = function (HASH) {
		// $http.get(BASE_URL + 'area/invoices/check_payment/' + INVOICEID).then(function (Data) {
		// 	$scope.response = Data.data;
		// 	if ($scope.response === true) {
				window.location.href = BASE_URL + 'gateway/paypal/' + HASH;
		// 	} else {
		// 		$.gritter.add({
		// 			title: '<b>Error</b>',
		// 			text: $scope.response.message,
		// 			class_name: 'color danger',
		// 		});
		// 	}
		// });
	};
	
	$scope.PayViaAuthorize = function (HASH) {
		window.location.href = BASE_URL + 'gateway/authorize/' + HASH;
	};

	$scope.PayViaPayUMoney = function (HASH) {
		window.location.href = BASE_URL + 'gateway/payumoney/' + HASH;
	};

	$scope.PayViaCCAvenue = function (HASH) {
		window.location.href = BASE_URL + 'gateway/ccavenue/' + HASH;
	};

	$scope.PayViaStripe = function (HASH) {
		window.location.href = BASE_URL + 'gateway/stripe/' + HASH;
	};

	$scope.PayViaRazorpay = function (HASH) {
		window.location.href = BASE_URL + 'gateway/razorpay/' + HASH;
	};

	$scope.Discussions = buildToggler('Discussions');

	function buildToggler(navID) {
		return function () {
			$mdSidenav(navID).toggle();

		};
	}

	$scope.close = function () {
		$mdSidenav('Discussions').close();
	};
	$scope.CloseModal = function () {
		$mdDialog.hide();
	};

	$http.get(BASE_URL + 'area/api/get_discussions/invoice/' + INVOICEID).then(function (Discussions) {
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
					staff_id: discussion.staff_id,
					full_name: ACTIVE_CONTACT_NAME,

				});
				var config = {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
					}
				};
				var posturl = BASE_URL + 'area/api/add_discussion_comment';
				$http.post(posturl, dataObj, config)
					.then(
						function (response) {
							console.log(response);
							$scope.discussions_comments.push({
								'content': discussion.newcontent,
								'full_name': ACTIVE_CONTACT_NAME,
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

	$http.get(BASE_URL + 'area/api/get_invoicedetails/' + INVOICEID).then(function (InvoiceDetails) {
		$scope.invoice = InvoiceDetails.data;
		$scope.paymentError = false;
		$scope.paymentOption = true;
		$scope.checkBalance = function(value) {
			if ($scope.payBalance > $scope.invoice.balance) {
				$scope.paymentOption = false;
				$scope.paymentError = true;
			} else {
				$scope.paymentOption = true;
				$scope.paymentError = false;
			}

			// var dataObj = $.param({
			// 	payment: $scope.payBalance,
			// });
			// var config = {
			// 	headers: {
			// 		'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
			// 	}
			// };
			// var posturl = BASE_URL + 'area/invoices/save_amount/' + INVOICEID;
			// $http.post(posturl, dataObj, config)
			// 	.then(
			// 		function (response) {
			// 			console.log(response);
			// 		},
			// 		function (response) {
			// 			console.log(response);
			// 		}
			// 	);
		}
	});

}

function Proposals_Controller($scope, $http) {
	"use strict";
	$http.get(BASE_URL + 'area/api/get_proposals').then(function (Proposals) {
		$scope.proposals = Proposals.data;
		$scope.search = {
			subject: '',
		};
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

function Proposal_Controller($scope, $http) {
	"use strict";
	$http.get(BASE_URL + 'area/api/get_products').then(function (Products) {
		$scope.products = Products.data;
	});
}

function Projects_Controller($scope, $http) {
	"use strict";
	$http.get(BASE_URL + 'area/api/get_projects').then(function (Projects) {
		$scope.projects = Projects.data;
		$scope.pinnedprojects = Projects.data;
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

function Tickets_Controller($scope, $http, $mdSidenav) {
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

	$http.get(BASE_URL + 'area/api/get_tickets').then(function (Tickets) {
		$scope.tickets = Tickets.data;
		$scope.GoTicket = function (TICKETID) {
			window.location.href = BASE_URL + 'area/tickets/ticket/' + TICKETID;
		};
		$scope.search = {
			subject: '',
			message: ''
		};
	});

	$http.get(BASE_URL + 'area/api/get_customers').then(function (Customers) {
		$scope.customers = Customers.data;
	});

	$http.get(BASE_URL + 'area/api/get_departments').then(function (Departments) {
		$scope.departments = Departments.data;
	});

	$http.get(BASE_URL + 'area/api/get_contacts').then(function (Contacts) {
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

	$http.get(BASE_URL + 'area/api/get_ticket/' + TICKETID).then(function (TicketDetails) {
		$scope.ticket = TicketDetails.data;
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

}


CiuisCRM.controller('Area_Controller', Area_Controller);
CiuisCRM.controller('Invoices_Controller', Invoices_Controller);
CiuisCRM.controller('Invoice_Controller', Invoice_Controller);
CiuisCRM.controller('Proposals_Controller', Proposals_Controller);
CiuisCRM.controller('Proposal_Controller', Proposal_Controller);
CiuisCRM.controller('Projects_Controller', Projects_Controller);
CiuisCRM.controller('Project_Controller', Project_Controller);
CiuisCRM.controller('Tickets_Controller', Tickets_Controller);
CiuisCRM.controller('Ticket_Controller', Ticket_Controller);

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

CiuisCRM.directive('ciuisReady', function () {
	"use strict";
	return {
		link: function () {
			angular.element(document).ready(function () {
				$('#ciuis-logo-donder').addClass('animated rotateIn'); // Logo Transform
				$('#ciuisloader').hide();
			});
		}
	};
});

CiuisCRM.directive("calendar", function () {
	"use strict";
	return {
		restrict: "E",
		templateUrl: "calendar.html",
		scope: {
			selected: "=",
		},
		link: function (scope) {
			scope.selected = _removeTime(scope.selected || moment());
			scope.month = scope.selected.clone();

			var start = scope.selected.clone();
			start.date(1);
			_removeTime(start.day(0));

			_buildMonth(scope, start, scope.month);

			scope.select = function (day) {
				scope.selected = day.date;
				scope.$parent.CheckTimes(moment(scope.selected).format("YYYY-MM-DD"));
			};

			scope.next = function () {
				var next = scope.month.clone();
				_removeTime(next.month(next.month() + 1).date(1));
				scope.month.month(scope.month.month() + 1);
				_buildMonth(scope, next, scope.month);
			};

			scope.previous = function () {
				var previous = scope.month.clone();
				_removeTime(previous.month(previous.month() - 1).date(1));
				scope.month.month(scope.month.month() - 1);
				_buildMonth(scope, previous, scope.month);
			};
		}
	};

	function _removeTime(date) {
		return date.day(0).hour(0).minute(0).second(0).millisecond(0);
	}

	function _buildMonth(scope, start, month) {
		scope.weeks = [];
		var done = false,
			date = start.clone(),
			monthIndex = date.month(),
			count = 0;
		while (!done) {
			scope.weeks.push({
				days: _buildWeek(date.clone(), month)
			});
			date.add(1, "w");
			done = count++ > 2 && monthIndex !== date.month();
			monthIndex = date.month();
		}
	}

	function _buildWeek(date, month) {
		var days = [];
		for (var i = 0; i < 7; i++) {
			days.push({
				name: date.format("dd").substring(0, 1),
				number: date.date(),
				isCurrentMonth: date.month() === month.month(),
				isToday: date.isSame(new Date(), "day"),
				date: date
			});
			date = date.clone();
			date.add(1, "d");
		}
		return days;
	}
});
