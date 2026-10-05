const mercureUrl = new URL(
    'https://localhost:8443/.well-known/mercure'
);

document.querySelectorAll('[data-meeting-id]').forEach(card => {
    const meetingId = card.dataset.meetingId;

    console.log(meetingId);

    mercureUrl.searchParams.append(
        'match',
        `https://localhost:8443/api/meetings/${meetingId}`
    );
});

console.log(mercureUrl.toString());

const eventSource = new EventSource(mercureUrl);

eventSource.onopen = () => {
    console.log('Mercure connected');
};

eventSource.onmessage = event => {
    console.log('RAW EVENT:', event);

    const meeting = JSON.parse(event.data);

    updateMeetingCard(meeting);
    updateMeetingDetails(meeting);

    console.log('Meeting updated:', meeting);
};

eventSource.onerror = event => {
    console.error('Mercure error:', event);
};

function updateMeetingCard(meeting) {
    let card = document.querySelector(".meeting-card[data-meeting-id='" + meeting.id + "']");

    if (!card) {
        return;
    }

    // Update card attributes
    //card.dataset.meetingStatus = meeting.status;

    // Name
    const name = card.querySelector('.meeting-name');
    if (name) {
        name.textContent = meeting.name;
    }

    // Summary
    const summary = card.querySelector('.meeting-summary');
    if (summary) {
        summary.textContent = meeting.summary ?? '';
    }

    // Actions
    const actions = card.querySelector('.meeting-actions');
    if (actions) {
        // Build whatever state the meeting is currently in
        if (!meeting.activeProcess) {
            if (!meeting.transcript?.length) {
                actions.innerHTML = `
                    <form method="post"
                          action="/meetings/${meeting.id}/transcribe">
                        <button type="submit" class="btn btn-primary">
                            Transcribe
                        </button>
                    </form>
                `;
            } else if (!meeting.summary) {
                actions.innerHTML = `
                    <form method="post"
                          action="/meetings/${meeting.id}/transcribe">
                        <button type="submit" class="btn btn-warning">
                            Analyze
                        </button>
                    </form>
                `;
            }
        }
    }
}

function updateMeetingDetails(meeting) {
    const details = document.querySelector(
        `.meeting-details[data-meeting-id="${meeting.id}"]`
    );

    if (!details) {
        return;
    }

    const name = details.querySelector('.meeting-name');

    if (name) {
        name.textContent = meeting.name;
    }

    const summary = details.querySelector('.meeting-summary');

    if (summary) {
        summary.textContent = meeting.summary ?? '';
    }

    const audio = details.querySelector('.meeting-audio');

    if (audio) {
        audio.innerHTML = `
            <audio id="player" controls preload="metadata">
                <source src="/build/audios/${meeting.audio}" type="audio/mp3"/>
            </audio>
        `;
    }

    const transcription = details.querySelector('.meeting-transcription');

    if (transcription) {
        let innerHtml = ''

        meeting.transcript.forEach(line => {
            innerHtml +=`
                    <div class="row hoverable rounded-2 p-2 segment a-slide-in" data-end="${line.end}" data-start="${line.start}">
                        <p class="col-auto text-body fw-bold mb-0"> ${line.user}</p>
                        <p class="col-auto text-secondary mb-0 fw-light fs-7 my-auto">${line.start}</p>
                        <div class="row">
                            <p class="col-auto text-body mb-0">${line.text}</p>
                            </div>
                        </div>
                `;
        });

        transcription.innerHTML = innerHtml;
    }
}
