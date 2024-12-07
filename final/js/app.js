// Static data for demonstration
const novels = [
    { id: 1, title: "The Adventures of Sherlock Holmes", author: "Arthur Conan Doyle", date: "14-18-1892", chapters: ["Chapter 1.1", "Chapter 1.2"], cover:"https://d28hgpri8am2if.cloudfront.net/book_images/onix/cvr9781607102113/the-adventures-of-sherlock-holmes-and-other-stories-9781607102113_hr.jpg" },
    { id: 2, title: "The Great Gatsby", author: "F.Scott Fitzegerald", date: "10-4-1925", chapters: ["Chapter 2.1", "Chapter 2.2"], cover:"https://d28hgpri8am2if.cloudfront.net/book_images/onix/cvr9781524879761/the-great-gatsby-9781524879761_hr.jpg" }
];

// Home Page: Populate Novel Table
if (document.querySelector("#novel-table")) {
    const novelTable = document.getElementById("novel-table");
    novels.forEach(novel => {
        const novelItem = document.createElement("div");
        novelItem.classList.add("novel-item");
        novelItem.innerHTML = `
            <a href="novel.html?id=${novel.id}"><img src="novel${novel.cover}.jpg" alt="${novel.title}"></a>
            <h3><a href="novel.html?id=${novel.id}">${novel.title}</a></h3>
            <h4>${novel.author}</h4>
        `;
        novelTable.appendChild(novelItem);
    });
}

// Novel Page: Populate Novel Details
if (document.querySelector("#novel-title")) {
    const params = new URLSearchParams(window.location.search);
    const novelId = parseInt(params.get("id"));
    const novel = novels.find(n => n.id === novelId);
    if (novel) {
        document.getElementById("novel-title").textContent = novel.title;
        document.getElementById("novel-author").textContent += novel.author;
        document.getElementById("novel-date").textContent += novel.date;

        const chaptersList = document.getElementById("chapters-list");
        novel.chapters.forEach((chapter, index) => {
            const chapterItem = document.createElement("li");
            chapterItem.innerHTML = `<a href="chapter.html?id=${novel.id}&chapter=${index}">${chapter}</a>`;
            chaptersList.appendChild(chapterItem);
        });
    }
}

// Chapter Page: Populate Chapter Content
if (document.querySelector("#chapter-title")) {
    const params = new URLSearchParams(window.location.search);
    const novelId = parseInt(params.get("id"));
    const chapterIndex = parseInt(params.get("chapter"));
    const novel = novels.find(n => n.id === novelId);

    if (novel) {
        const chapterTitle = `Chapter ${chapterIndex + 1}`;
        const chapterContent = novel.chapters[chapterIndex];

        document.getElementById("chapter-title").textContent = chapterTitle;
        document.getElementById("chapter-content").textContent = chapterContent;

        document.getElementById("prev-chapter").onclick = () => {
            if (chapterIndex > 0) {
                window.location.href = `chapter.html?id=${novelId}&chapter=${chapterIndex - 1}`;
            }
        };

        document.getElementById("next-chapter").onclick = () => {
            if (chapterIndex < novel.chapters.length - 1) {
                window.location.href = `chapter.html?id=${novelId}&chapter=${chapterIndex + 1}`;
            }
        };
    }
}
