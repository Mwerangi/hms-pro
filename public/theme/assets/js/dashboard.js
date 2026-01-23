/**
 * Dashboard JavaScript - MicroFinance Pro
 * Handles chart rendering, data interactions, and UI enhancements
 */

(function() {
  "use strict";

  /**
   * Initialize dashboard when DOM is ready
   */
  document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
      initCharts();
    }

    // Initialize other dashboard features
    initSidebarToggle();
    initTableFeatures();
    initFormValidation();
    initTooltips();
  });

  /**
   * Initialize all charts
   */
  function initCharts() {
    // Loan Performance Chart (Line Chart)
    const loanChartCanvas = document.getElementById('loanChart');
    if (loanChartCanvas) {
      const loanChartCtx = loanChartCanvas.getContext('2d');
      new Chart(loanChartCtx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          datasets: [{
            label: 'Disbursements',
            data: [420000, 385000, 450000, 475000, 520000, 490000, 510000, 535000, 560000, 580000, 595000, 610000],
            borderColor: '#4154f1',
            backgroundColor: 'rgba(65, 84, 241, 0.1)',
            tension: 0.4,
            fill: true
          }, {
            label: 'Collections',
            data: [320000, 345000, 380000, 395000, 410000, 430000, 445000, 460000, 480000, 495000, 510000, 487320],
            borderColor: '#2eca6a',
            backgroundColor: 'rgba(46, 202, 106, 0.1)',
            tension: 0.4,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: 'top',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  let label = context.dataset.label || '';
                  if (label) {
                    label += ': ';
                  }
                  label += '$' + context.parsed.y.toLocaleString();
                  return label;
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '$' + (value / 1000) + 'k';
                }
              }
            }
          }
        }
      });
    }

    // Portfolio Pie Chart
    const pieChartCanvas = document.getElementById('pieChart');
    if (pieChartCanvas) {
      const pieChartCtx = pieChartCanvas.getContext('2d');
      new Chart(pieChartCtx, {
        type: 'doughnut',
        data: {
          labels: ['Active Loans', 'Performing', 'At Risk', 'Non-Performing'],
          datasets: [{
            data: [65, 25, 7, 3],
            backgroundColor: [
              '#4154f1',
              '#2eca6a',
              '#ff771d',
              '#ff4444'
            ],
            borderWidth: 2,
            borderColor: '#fff'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.label + ': ' + context.parsed + '%';
                }
              }
            }
          }
        }
      });
    }
  }

  /**
   * Initialize sidebar toggle for mobile
   */
  function initSidebarToggle() {
    const body = document.querySelector('body');
    const sidebarToggle = document.querySelector('.mobile-nav-toggle');
    
    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        body.classList.toggle('toggle-sidebar');
        
        // Toggle icon between list and X
        if (body.classList.contains('toggle-sidebar')) {
          sidebarToggle.classList.remove('bi-list');
          sidebarToggle.classList.add('bi-x');
        } else {
          sidebarToggle.classList.remove('bi-x');
          sidebarToggle.classList.add('bi-list');
        }
      });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
      if (window.innerWidth < 1200) {
        if (!e.target.closest('.sidebar') && !e.target.closest('.mobile-nav-toggle')) {
          if (body.classList.contains('toggle-sidebar')) {
            body.classList.remove('toggle-sidebar');
            if (sidebarToggle) {
              sidebarToggle.classList.remove('bi-x');
              sidebarToggle.classList.add('bi-list');
            }
          }
        }
      }
    });
  }

  /**
   * Initialize table features
   */
  function initTableFeatures() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
      selectAllCheckbox.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
          checkbox.checked = selectAllCheckbox.checked;
        });
      });
    }

    // Table row click to expand/collapse details
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
      row.style.cursor = 'pointer';
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('.table tbody tr');
        
        tableRows.forEach(row => {
          const text = row.textContent.toLowerCase();
          if (text.includes(searchTerm)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    }
  }

  /**
   * Initialize form validation
   */
  function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate="true"]');
    
    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      });
    });
  }

  /**
   * Initialize Bootstrap tooltips
   */
  function initTooltips() {
    if (typeof bootstrap !== 'undefined') {
      const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
      );
      tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }
  }

  /**
   * Format currency values
   */
  window.formatCurrency = function(amount) {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD'
    }).format(amount);
  };

  /**
   * Format date values
   */
  window.formatDate = function(date) {
    return new Intl.DateFormat('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    }).format(new Date(date));
  };

  /**
   * Show loading spinner
   */
  window.showLoader = function(element) {
    if (element) {
      element.innerHTML = '<div class="spinner"></div>';
    }
  };

  /**
   * Show toast notification
   */
  window.showNotification = function(message, type = 'info') {
    // Simple notification implementation
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
      notification.remove();
    }, 5000);
  };

  /**
   * Confirm action with user
   */
  window.confirmAction = function(message, callback) {
    if (confirm(message)) {
      callback();
    }
  };

  /**
   * Export table to CSV
   */
  window.exportTableToCSV = function(tableId, filename = 'export.csv') {
    const table = document.getElementById(tableId);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
      const cols = row.querySelectorAll('td, th');
      const csvRow = [];
      cols.forEach(col => {
        csvRow.push('"' + col.textContent.trim().replace(/"/g, '""') + '"');
      });
      csv.push(csvRow.join(','));
    });

    // Download
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  /**
   * Refresh dashboard data
   */
  window.refreshDashboard = function() {
    showNotification('Refreshing dashboard data...', 'info');
    
    // Simulate API call
    setTimeout(() => {
      showNotification('Dashboard refreshed successfully!', 'success');
      // In production, this would fetch and update real data
    }, 1000);
  };

  /**
   * Calculate loan payment schedule
   */
  window.calculateLoanPayment = function(principal, annualRate, months) {
    const monthlyRate = annualRate / 100 / 12;
    const payment = principal * (monthlyRate * Math.pow(1 + monthlyRate, months)) / 
                   (Math.pow(1 + monthlyRate, months) - 1);
    return payment;
  };

  /**
   * Generate payment schedule
   */
  window.generatePaymentSchedule = function(principal, annualRate, months) {
    const monthlyPayment = calculateLoanPayment(principal, annualRate, months);
    const schedule = [];
    let balance = principal;
    const monthlyRate = annualRate / 100 / 12;

    for (let i = 1; i <= months; i++) {
      const interest = balance * monthlyRate;
      const principalPayment = monthlyPayment - interest;
      balance -= principalPayment;

      schedule.push({
        month: i,
        payment: monthlyPayment,
        principal: principalPayment,
        interest: interest,
        balance: Math.max(0, balance)
      });
    }

    return schedule;
  };

  /**
   * Update real-time statistics
   */
  window.updateStatistics = function() {
    // Simulate real-time updates
    const stats = document.querySelectorAll('.info-card h6');
    stats.forEach(stat => {
      // Add pulse animation
      stat.classList.add('animate__animated', 'animate__pulse');
      setTimeout(() => {
        stat.classList.remove('animate__animated', 'animate__pulse');
      }, 1000);
    });
  };

  // Auto-refresh statistics every 30 seconds
  setInterval(() => {
    if (document.querySelector('.dashboard-page')) {
      updateStatistics();
    }
  }, 30000);

  /**
   * Initialize date range picker if available
   */
  if (typeof flatpickr !== 'undefined') {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
      flatpickr(input, {
        dateFormat: 'Y-m-d',
        allowInput: true
      });
    });
  }

  /**
   * Handle keyboard shortcuts
   */
  document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K for search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault();
      const searchInput = document.querySelector('#searchInput, #searchCustomer');
      if (searchInput) {
        searchInput.focus();
      }
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
      const openModal = document.querySelector('.modal.show');
      if (openModal) {
        const modalInstance = bootstrap.Modal.getInstance(openModal);
        if (modalInstance) {
          modalInstance.hide();
        }
      }
    }
  });

  // Log dashboard initialization
  console.log('MicroFinance Pro Dashboard initialized');

})();
