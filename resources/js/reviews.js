import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    const clampParagraphs = document.querySelectorAll('.review-content');
    clampParagraphs.forEach(p => {
        const container = p.parentElement;
        const more = container.querySelector('.read-more');
        if (!more) return;
        more.addEventListener('click', (e) => {
            e.preventDefault();
            p.classList.remove('line-clamp-3');
            more.remove();
        });
    });

    document.querySelectorAll('.like-btn, .dislike-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const url = btn.dataset.url;
            const type = btn.dataset.type;
            try {
                const { data } = await axios.post(url, { type });
                const parent = btn.closest('.review-card');
                parent.querySelectorAll(`[data-type="like"] span`).forEach(s => s.textContent = data.likes);
                parent.querySelectorAll(`[data-type="dislike"] span`).forEach(s => s.textContent = data.dislikes);
                btn.classList.add('text-red-600');
            } catch (err) { }
        });
    });

    const filterLinks = document.querySelectorAll('.reviews-filter a');
    filterLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const filter = link.dataset.filter;
            filterLinks.forEach(l => l.classList.remove('text-indigo-600', 'font-semibold'));
            link.classList.add('text-indigo-600', 'font-semibold');
            document.querySelectorAll('.review-card').forEach(card => {
                const rating = parseInt(card.dataset.rating);
                if (filter === 'negative') {
                    card.classList.toggle('hidden', rating > 2);
                } else if (filter === 'positive') {
                    card.classList.toggle('hidden', rating < 4);
                } else {
                    card.classList.remove('hidden');
                }
            });
        });
    });
});
