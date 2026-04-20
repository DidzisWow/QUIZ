/* ============================================================
   quiz-show.js — Answer selection + progress + timer
   ============================================================ */

const total = document.querySelectorAll('.question-block').length;

/**
 * Called when a radio input changes.
 * Highlights the selected answer and updates the progress bar.
 */
function selectAnswer(input) {
    // Remove selected state from all options in this question
    document.querySelectorAll(`[name="${input.name}"]`).forEach(radio => {
        radio.closest('.answer-option').classList.remove('selected');
    });

    // Mark this option as selected
    input.closest('.answer-option').classList.add('selected');

    // Count how many questions have been answered
    const checked = document.querySelectorAll('#quiz-form input[type="radio"]:checked');
    const answered = new Set([...checked].map(r => r.name)).size;

    // Update counter text
    document.getElementById('answered-count').textContent = `${answered} / ${total} answered`;

    // Update progress bar width
    document.getElementById('progress-fill').style.width = `${(answered / total) * 100}%`;
}

/**
 * Countdown timer — auto-submits the form when time runs out.
 * Called from the blade only when the quiz has a time_limit.
 *
 * @param {number} seconds  Total seconds for the quiz
 */
function startTimer(seconds) {
    const display = document.getElementById('timer-display');

    function tick() {
        const m = Math.floor(seconds / 60).toString().padStart(2, '0');
        const s = (seconds % 60).toString().padStart(2, '0');
        display.textContent = `${m}:${s}`;

        if (seconds <= 30) display.classList.add('warn');

        if (seconds <= 0) {
            document.getElementById('quiz-form').submit();
            return;
        }

        seconds--;
        setTimeout(tick, 1000);
    }

    tick();
}