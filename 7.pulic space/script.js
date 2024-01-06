document.addEventListener('DOMContentLoaded', () => {
    loadPosts();
});

function submitPost() {
    const content = document.getElementById('post-content').value;
    const fileInput = document.getElementById('file-input');
    const file = fileInput.files[0];

    if (content.trim() === '' && !file) {
        alert('Please enter some content or attach a file.');
        return;
    }

    const post = {
        content,
        timestamp: new Date().getTime(),
    };

    if (file) {
        const reader = new FileReader();
        reader.onload = function () {
            post.mediaType = file.type.startsWith('image') ? 'image' : 'video';
            post.media = reader.result;
            savePost(post);
            loadPosts();
        };
        reader.readAsDataURL(file);
    } else {
        savePost(post);
        loadPosts();
    }

    document.getElementById('post-content').value = '';
    fileInput.value = '';
}

function savePost(post) {
    let posts = JSON.parse(localStorage.getItem('posts')) || [];
    posts.push(post);
    localStorage.setItem('posts', JSON.stringify(posts));
}

function loadPosts() {
    const postsContainer = document.getElementById('posts-container');
    postsContainer.innerHTML = '';

    const posts = JSON.parse(localStorage.getItem('posts')) || [];

    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.classList.add('post');
        postElement.innerHTML = `
            <p>${post.content}</p>
            ${post.mediaType === 'image' ? `<img src="${post.media}" alt="Media">` : ''}
            ${post.mediaType === 'video' ? `<video controls><source src="${post.media}" type="video/mp4"></video>` : ''}
            <p>${new Date(post.timestamp).toLocaleString()}</p>
        `;
        postsContainer.appendChild(postElement);
    });
}
