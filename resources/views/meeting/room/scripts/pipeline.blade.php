// ======================== PIPELINE & NOTULENSI ========================
let pipelinePollTimer = null;

function applyPipelinePayload(data) {
    const st = data.pipeline_status || 'idle';
    const stage = data.pipeline_stage ? ` (${data.pipeline_stage})` : '';
    const err = data.pipeline_error ? ` — ${data.pipeline_error}` : '';
    const pipelineStatusEl = document.getElementById('pipelineStatus');
    if (pipelineStatusEl) {
        pipelineStatusEl.textContent = 'Pipeline: ' + st + stage + err;
        pipelineStatusEl.className = 'text-xs ' + (st === 'failed' ? 'text-red-400' : st === 'completed' ?
            'text-emerald-400' : 'text-amber-200');
    }
    if (pdfBtn && data.has_pdf) {
        pdfBtn.classList.remove('opacity-40', 'pointer-events-none');
        pdfBtn.setAttribute('href', notulensiPdfUrl);
    }
}

async function refreshPipelineStatus() {
    try {
        const res = await fetch(baseUrl + '/pipeline-status', {
            headers: {
                'Accept': 'application/json'
            }
        });
        if (!res.ok) return;
        const data = await res.json();
        applyPipelinePayload(data);
        if (data.pipeline_status !== 'processing' && pipelinePollTimer) {
            clearInterval(pipelinePollTimer);
            pipelinePollTimer = null;
        }
    } catch (e) {
        console.warn(e);
    }
}

function startPipelinePolling() {
    if (pipelinePollTimer) clearInterval(pipelinePollTimer);
    pipelinePollTimer = setInterval(refreshPipelineStatus, 4000);
    refreshPipelineStatus();
}

async function triggerGeminiNotulensi() {
    showAiLoading(true);
    try {
        const res = await fetch(baseUrl + '/generate-notulensi', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        showAiLoading(false);
        if (!res.ok) throw new Error(data.message || 'Gagal membuat notulensi');
        if (!data.notulensi || !data.notulensi.id) {
            throw new Error('Notulensi tidak tersimpan ke database (ID kosong)');
        }
        renderNotulensiModal(data.notulensi, data.pdf_url);
        if (showNotulensiBtn) showNotulensiBtn.classList.remove('hidden');
        if (pdfBtn) {
            pdfBtn.classList.remove('opacity-40', 'pointer-events-none');
            pdfBtn.setAttribute('href', data.pdf_url);
        }
        openNotulensiModal(true);
    } catch (err) {
        showAiLoading(false);
        alert(err.message);
    }
}

function showAiLoading(show) {
    const overlay = document.getElementById('aiLoadingOverlay');
    if (!overlay) return;
    const isMobile = window.innerWidth <= 767;
    if (show) {
        if (isMobile) {
            overlay.style.position = 'fixed';
            overlay.style.bottom = '80px';
            overlay.style.left = '50%';
            overlay.style.transform = 'translateX(-50%)';
            overlay.style.zIndex = '200';
        } else {
            overlay.style.position = '';
            overlay.style.bottom = '';
            overlay.style.left = '';
            overlay.style.transform = '';
            overlay.style.zIndex = '';
        }
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.add('opacity-100'), 50);
    } else {
        overlay.style.position = '';
        overlay.style.bottom = '';
        overlay.style.left = '';
        overlay.style.transform = '';
        overlay.style.zIndex = '';
        overlay.classList.remove('opacity-100');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }
}

function showRecordingPopup(show, name) {
    const popup = document.getElementById('recordingPopup');
    if (!popup) return;
    const nameEl = document.getElementById('recordingByName');
    if (nameEl && name) nameEl.textContent = 'oleh ' + name;
    if (nameEl && !name) nameEl.textContent = 'oleh Anda';
    const isMobile = window.innerWidth <= 767;
    if (show) {
        if (isMobile) {
            popup.style.position = 'fixed';
            popup.style.bottom = '80px';
            popup.style.left = '50%';
            popup.style.transform = 'translateX(-50%)';
            popup.style.zIndex = '200';
        } else {
            popup.style.position = '';
            popup.style.bottom = '';
            popup.style.left = '';
            popup.style.transform = '';
            popup.style.zIndex = '';
        }
        popup.classList.remove('hidden');
        setTimeout(() => popup.classList.add('opacity-100'), 50);
    } else {
        popup.style.position = '';
        popup.style.bottom = '';
        popup.style.left = '';
        popup.style.transform = '';
        popup.style.zIndex = '';
        popup.classList.remove('opacity-100');
        setTimeout(() => popup.classList.add('hidden'), 300);
    }
}

function hideRecordingPopup() {
    showRecordingPopup(false);
}

function openNotulensiModal(open) {
    if (!notulensiModal) return;
    if (open) {
        notulensiModal.classList.remove('hidden');
        setTimeout(() => {
            notulensiModal.classList.add('opacity-100');
            notulensiModal.firstElementChild.classList.remove('scale-95');
            notulensiModal.firstElementChild.classList.add('scale-100');
        }, 50);
    } else {
        notulensiModal.classList.remove('opacity-100');
        notulensiModal.firstElementChild.classList.remove('scale-100');
        notulensiModal.firstElementChild.classList.add('scale-95');
        setTimeout(() => notulensiModal.classList.add('hidden'), 300);
    }
}

function renderNotulensiModal(notulensi, pdfUrl) {
    document.getElementById('modalRingkasan').textContent = notulensi.ringkasan || '-';
    document.getElementById('modalPdfBtn').setAttribute('href', pdfUrl);
    const s = notulensi.structured_summary || {};
    const topikEl = document.getElementById('modalTopik');
    topikEl.innerHTML = (s.topik_dibahas || []).map((t, i) =>
        `<li class="flex items-start gap-2.5"><span class="flex-shrink-0 w-5 h-5 rounded-full text-xs font-bold flex items-center justify-center mt-0.5" style="background:rgba(16,185,129,0.12);color:#34d399">${i + 1}</span><span class="text-sm leading-relaxed text-gray-300">${escapeHtml(t)}</span></li>`
    ).join('') || '<li class="text-sm text-gray-500 italic">-</li>';
    document.getElementById('modalKeputusan').innerHTML = (s.keputusan || []).map(k =>
        `<li class="flex items-start gap-2.5"><svg class="flex-shrink-0 w-4 h-4 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="color:#fbbf24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span class="text-sm leading-relaxed text-gray-300">${escapeHtml(k)}</span></li>`
    ).join('') || '<li class="text-sm text-gray-500 italic">-</li>';
    const actionItems = s.action_items || [];
    const tbody = document.getElementById('modalActionItems');
    if (actionItems.length) tbody.innerHTML = actionItems.map(ai =>
        `<tr><td class="px-4 py-3 text-sm text-gray-300">${escapeHtml(ai.task||'-')}</td><td class="px-4 py-3"><span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full" style="background:rgba(139,92,246,0.1);color:#a78bfa"><svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>${escapeHtml(ai.pic||'-')}</span></td><td class="px-4 py-3"><span class="inline-flex items-center gap-1.5 text-xs" style="color:#9ca3af"><svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>${escapeHtml(ai.deadline||'-')}</span></td></tr>`
    ).join('');
    else tbody.innerHTML =
        '<tr><td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500 italic">-</td></tr>';
    document.getElementById('modalRisiko').innerHTML = (s.risiko_catatan || []).map(r =>
        `<li class="flex items-start gap-2.5"><svg class="flex-shrink-0 w-4 h-4 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#9ca3af"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-sm leading-relaxed text-gray-300">${escapeHtml(r)}</span></li>`
    ).join('') || '<li class="text-sm text-gray-500 italic">-</li>';
}
