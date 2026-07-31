/**
 * Track Citations — Non-Blocking Chunked Background Ticket Export System
 * Pure self-contained widget with explicit CSS styling for guaranteed rendering
 * Features automatic page reload & navigation persistence via server status check!
 * Includes custom inline cancellation confirmation & explicit Close (x) buttons with dismissal cleanup.
 */

(function () {
    'use strict';

    let currentExportId = null;
    let isCancelled = false;
    let isProcessing = false;

    // Helper to format numbers with commas
    function formatNumber(num) {
        return (num || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // Get CSRF Token
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // Inject Widget HTML into DOM if not present
    function ensureWidgetDOM() {
        if (document.getElementById('ticketExportModal')) return;

        const widgetHtml = `
        <div id="ticketExportModal" style="display: none; position: fixed; bottom: 24px; right: 24px; z-index: 999999; width: 380px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15), 0 10px 10px -5px rgba(0,0,0,0.04); padding: 16px; font-family: system-ui, -apple-system, sans-serif; transition: all 0.25s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div id="exportIconBox" style="width: 32px; height: 32px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        <i class="ti ti-download" style="font-size: 18px;"></i>
                    </div>
                    <div>
                        <h6 style="font-weight: 700; color: #0f172a; font-size: 14px; margin: 0; line-height: 1.2;">Exporting Tickets</h6>
                        <span id="exportStatusText" style="font-size: 12px; color: #64748b;">Preparing export...</span>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 2px;">
                    <button type="button" id="exportMinimizeBtn" style="border: none; background: transparent; color: #94a3b8; cursor: pointer; padding: 4px 6px; border-radius: 6px; font-size: 14px;" title="Minimize / Expand">
                        <i class="ti ti-minus"></i>
                    </button>
                    <button type="button" id="exportCloseHeaderBtn" style="border: none; background: transparent; color: #94a3b8; cursor: pointer; padding: 4px 6px; border-radius: 6px; font-size: 14px;" title="Close & Dismiss">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>

            <div id="exportBodyContent" style="display: block;">
                <div id="exportProgressSection" style="margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; font-weight: 600; margin-bottom: 6px;">
                        <span id="exportCountDetail" style="color: #475569;">0 / 0 tickets</span>
                        <span id="exportPercentText" style="color: #4f46e5;">0%</span>
                    </div>
                    <div style="width: 100%; background: #f1f5f9; height: 10px; border-radius: 9999px; overflow: hidden;">
                        <div id="exportProgressBar" style="background: #4f46e5; height: 100%; width: 0%; border-radius: 9999px; transition: width 0.3s ease;"></div>
                    </div>
                </div>

                <div id="exportControlsBox" style="display: flex; align-items: center; justify-content: space-between; gap: 8px; padding-top: 8px; border-top: 1px solid #f1f5f9;">
                    <button type="button" id="exportCancelBtn" style="border: none; background: #fef2f2; color: #dc2626; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="ti ti-x"></i> Cancel Export
                    </button>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button type="button" id="exportDoneCloseBtn" style="display: none; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">Close</button>
                        <a id="exportDownloadBtn" href="#" style="display: none; background: #059669; color: #ffffff; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" download>
                            <i class="ti ti-file-download"></i> Download Excel File
                        </a>
                    </div>
                </div>

                <!-- Custom Inline Cancellation Confirmation Overlay -->
                <div id="exportCancelConfirmBox" style="display: none; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 12px; margin-top: 10px;">
                    <div style="font-size: 13px; font-weight: 700; color: #9f1239; margin-bottom: 3px;">Cancel ticket export?</div>
                    <div style="font-size: 12px; color: #be123c; margin-bottom: 10px; line-height: 1.3;">This will stop the export process and remove the temporary file.</div>
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                        <button type="button" id="confirmKeepBtn" style="border: 1px solid #cbd5e1; background: #ffffff; color: #334155; padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">Keep Exporting</button>
                        <button type="button" id="confirmStopBtn" style="border: none; background: #e11d48; color: #ffffff; padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">Yes, Cancel</button>
                    </div>
                </div>
            </div>
        </div>`;

        document.body.insertAdjacentHTML('beforeend', widgetHtml);

        // Wire event listeners
        document.getElementById('exportCancelBtn').addEventListener('click', showCancelConfirm);
        document.getElementById('confirmKeepBtn').addEventListener('click', hideCancelConfirm);
        document.getElementById('confirmStopBtn').addEventListener('click', executeCancel);
        document.getElementById('exportMinimizeBtn').addEventListener('click', toggleMinimize);
        document.getElementById('exportCloseHeaderBtn').addEventListener('click', closeAndDismissWidget);
        document.getElementById('exportDoneCloseBtn').addEventListener('click', closeAndDismissWidget);
    }

    function showCancelConfirm() {
        document.getElementById('exportCancelConfirmBox').style.display = 'block';
        document.getElementById('exportControlsBox').style.display = 'none';
    }

    function hideCancelConfirm() {
        document.getElementById('exportCancelConfirmBox').style.display = 'none';
        document.getElementById('exportControlsBox').style.display = 'flex';
    }

    function toggleMinimize() {
        const bodyContent = document.getElementById('exportBodyContent');
        const modal = document.getElementById('ticketExportModal');
        const minBtn = document.getElementById('exportMinimizeBtn');

        if (bodyContent.style.display === 'none') {
            bodyContent.style.display = 'block';
            modal.style.width = '380px';
            minBtn.innerHTML = '<i class="ti ti-minus"></i>';
        } else {
            bodyContent.style.display = 'none';
            modal.style.width = '220px';
            minBtn.innerHTML = '<i class="ti ti-maximize"></i>';
        }
    }

    function showWidget() {
        ensureWidgetDOM();
        const modal = document.getElementById('ticketExportModal');
        const bodyContent = document.getElementById('exportBodyContent');
        bodyContent.style.display = 'block';
        modal.style.display = 'block';
        modal.style.width = '380px';

        // Reset UI elements
        document.getElementById('exportStatusText').innerText = 'Initializing export...';
        document.getElementById('exportStatusText').style.color = '#64748b';
        document.getElementById('exportCountDetail').innerText = '0 / 0 tickets';
        document.getElementById('exportPercentText').innerText = '0%';
        document.getElementById('exportProgressBar').style.width = '0%';
        document.getElementById('exportProgressBar').style.background = '#4f46e5';
        document.getElementById('exportControlsBox').style.display = 'flex';
        document.getElementById('exportCancelBtn').style.display = 'inline-flex';
        document.getElementById('exportCancelConfirmBox').style.display = 'none';
        document.getElementById('exportDownloadBtn').style.display = 'none';
        document.getElementById('exportDoneCloseBtn').style.display = 'none';
        document.getElementById('exportIconBox').style.background = '#eef2ff';
        document.getElementById('exportIconBox').style.color = '#4f46e5';
    }

    function startExport(e) {
        if (e) e.preventDefault();
        if (isProcessing) {
            alert('An export is already running in the background.');
            return;
        }

        showWidget();
        isCancelled = false;
        isProcessing = true;

        // Gather current page URL parameters & form inputs
        const currentUrlParams = new URLSearchParams(window.location.search);
        const postData = new FormData();

        for (const [key, val] of currentUrlParams.entries()) {
            postData.append(key, val);
        }

        // Add filter inputs if present on page
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            const formData = new FormData(filterForm);
            for (const [key, val] of formData.entries()) {
                if (val && !postData.has(key)) {
                    postData.append(key, val);
                }
            }
        }

        fetch('/tickets/export/start', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
            body: postData,
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert(data.error || 'Failed to start export process.');
                hideWidget();
                return;
            }

            currentExportId = data.export_id;
            const total = data.total || 0;

            if (total === 0) {
                document.getElementById('exportStatusText').innerText = 'No matching tickets to export.';
                document.getElementById('exportCancelBtn').style.display = 'none';
                isProcessing = false;
                return;
            }

            document.getElementById('exportStatusText').innerText = `Processing ${formatNumber(total)} tickets...`;
            document.getElementById('exportCountDetail').innerText = `0 / ${formatNumber(total)} tickets`;

            // Start processing chunks
            processChunks(currentExportId, 0, 2500, total);
        })
        .catch(err => {
            console.error('Export Start Error:', err);
            alert('Failed to connect to export server.');
            hideWidget();
        });
    }

    function processChunks(exportId, offset, limit, total) {
        if (isCancelled || !isProcessing) return;

        const postData = new FormData();
        postData.append('export_id', exportId);
        postData.append('offset', offset);
        postData.append('limit', limit);

        fetch('/tickets/export/chunk', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
            body: postData,
        })
        .then(res => res.json())
        .then(data => {
            if (isCancelled) return;

            if (data.status === 'cancelled') {
                isProcessing = false;
                hideWidget();
                return;
            }

            if (data.error) {
                document.getElementById('exportStatusText').innerText = 'Export error: ' + data.error;
                document.getElementById('exportStatusText').style.color = '#ef4444';
                isProcessing = false;
                return;
            }

            const processed = data.processed || 0;
            const percentage = data.percentage || 0;

            // Update UI Progress
            document.getElementById('exportProgressBar').style.width = percentage + '%';
            document.getElementById('exportPercentText').innerText = percentage + '%';
            document.getElementById('exportCountDetail').innerText = `${formatNumber(processed)} / ${formatNumber(total)} tickets`;

            if (data.status === 'completed' || processed >= total) {
                // Completed!
                renderCompletedState(data.download_url);
            } else {
                // Process next chunk recursively
                processChunks(exportId, offset + limit, limit, total);
            }
        })
        .catch(err => {
            console.error('Chunk Export Error:', err);
            if (!isCancelled && isProcessing) {
                // Retry chunk after brief delay
                setTimeout(() => processChunks(exportId, offset, limit, total), 2000);
            }
        });
    }

    function renderCompletedState(downloadUrl) {
        isProcessing = false;
        document.getElementById('exportStatusText').innerText = 'Export Complete!';
        document.getElementById('exportStatusText').style.color = '#059669';
        document.getElementById('exportStatusText').style.fontWeight = 'bold';
        document.getElementById('exportProgressBar').style.width = '100%';
        document.getElementById('exportProgressBar').style.background = '#10b981';
        document.getElementById('exportIconBox').style.background = '#d1fae5';
        document.getElementById('exportIconBox').style.color = '#059669';
        document.getElementById('exportCancelBtn').style.display = 'none';

        document.getElementById('exportDoneCloseBtn').style.display = 'inline-block';
        const downloadBtn = document.getElementById('exportDownloadBtn');
        downloadBtn.href = downloadUrl;
        downloadBtn.style.display = 'inline-flex';
    }

    function closeAndDismissWidget() {
        executeCancel();
    }

    function executeCancel() {
        isCancelled = true;
        isProcessing = false;

        if (currentExportId) {
            fetch(`/tickets/export/cancel/${currentExportId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                },
            });
        }

        hideWidget();
    }

    function hideWidget() {
        const modal = document.getElementById('ticketExportModal');
        if (modal) modal.style.display = 'none';
        const confirmBox = document.getElementById('exportCancelConfirmBox');
        if (confirmBox) confirmBox.style.display = 'none';
        isProcessing = false;
        isCancelled = false;
        currentExportId = null;
    }

    // Check if there is an active export running in background on page load
    function checkActiveExportOnLoad() {
        fetch('/tickets/export/active', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.has_active && data.export_id) {
                currentExportId = data.export_id;
                showWidget();
                const total = data.total || 0;
                const processed = data.processed || 0;
                const percentage = data.percentage || 0;

                document.getElementById('exportStatusText').innerText = `Processing ${formatNumber(total)} tickets...`;
                document.getElementById('exportProgressBar').style.width = percentage + '%';
                document.getElementById('exportPercentText').innerText = percentage + '%';
                document.getElementById('exportCountDetail').innerText = `${formatNumber(processed)} / ${formatNumber(total)} tickets`;

                if (data.status === 'completed' || processed >= total) {
                    renderCompletedState(data.download_url);
                } else if (data.status === 'processing') {
                    isProcessing = true;
                    isCancelled = false;
                    // Seamlessly resume processing chunks from current offset
                    processChunks(currentExportId, processed, 2500, total);
                }
            }
        })
        .catch(err => {
            console.error('Active Export Check Error:', err);
        });
    }

    // Expose global trigger function
    window.startTicketExport = startExport;

    // Attach event listeners when DOM is loaded
    function initExportListeners() {
        ensureWidgetDOM();
        checkActiveExportOnLoad();

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('a[href*="tickets/export"], .js-download-tickets');
            if (btn && btn.id !== 'exportDownloadBtn' && (!btn.href || !btn.href.includes('/export/download/'))) {
                e.preventDefault();
                startExport(e);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initExportListeners);
    } else {
        initExportListeners();
    }

})();
