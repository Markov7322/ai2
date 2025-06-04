import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('messages-container');
    if (!container) return;

    const convId = container.dataset.conversation;
    const userId = parseInt(container.dataset.userId);
    let lastTs = container.dataset.lastTimestamp;

    const addMessage = (msg) => {
        const div = document.createElement('div');
        div.className = msg.sender_id === userId ? 'text-right' : 'text-left';
        div.innerHTML = `<div class="inline-block px-3 py-2 rounded ${msg.sender_id === userId ? 'bg-indigo-100' : 'bg-gray-100'}"><p>${msg.message}</p><span class="text-xs text-gray-500">${msg.created_at_formatted}</span></div>`;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
        lastTs = msg.created_at;
    };

    const fetchUpdates = async () => {
        try {
            const { data } = await axios.get(`/messages/${convId}/updates`, { params: { after: lastTs } });
            data.forEach(addMessage);
        } catch (e) { }
    };

    setInterval(fetchUpdates, 5000);

    const form = document.getElementById('message-form');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = form.querySelector('input[name="message"]');
        const text = input.value.trim();
        if (!text) return;
        try {
            const { data } = await axios.post(`/messages/${convId}`, { message: text });
            addMessage(data);
            input.value = '';
        } catch (e) { }
    });
});
