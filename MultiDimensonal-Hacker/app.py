from flask import Flask, render_template, request
import sqlite3


app = Flask(__name__)

def createBlogDB():
    conn = sqlite3.connect('blog.db')
    c = conn.cursor()
    c.execute('CREATE TABLE IF NOT EXISTS entry (id INTEGER PRIMARY KEY AUTOINCREMENT, cover TEXT, title TEXT, author TEXT, content TEXT)')
    allblogs = {
        "1": {
    "author": "Dr. ARM",
    "cover_image": "https://www.cnet.com/a/img/resize/3395f0c285dacda1aa6c72dc3be2ed51da8ad566/hub/2019/07/31/41401431-0ccf-48cc-8701-f4d6121912ce/milkybarkid.jpg?auto=webp&fit=crop&height=675&width=1200",
    "title": "Hacking the Uncharted Realms of the Milky Way",
    "content": "In a daring expedition through the cosmic tapestry, Multidimension Cybersecurity, led by the enigmatic Dr. ARM, embarked on a journey to secure the uncharted planets of the Milky Way galaxy. As we navigated through the astral dimensions, vulnerabilities spanning space and time began to unveil themselves. Through a fusion of technological prowess and multidimensional hacking abilities, we confronted challenges that transcended the conventional scope of cybersecurity. Our efforts led to a new paradigm in safeguarding celestial frontiers, ensuring that even the distant realms beyond Earth remain fortified against digital threats."
  },
    "2": {
    "author": "Dr. ARM",
    "cover_image": "https://www.securitymagazine.com/ext/resources/images/cyberspace-freepik1170x658.jpg?1659970776",
    "title": "Fortifying Exoplanetary Networks Against Cyber Intrusions",
    "content": "The Multidimension Cybersecurity team, under the astute guidance of Dr. ARM, delves into the intricacies of exoplanetary security. As we traverse the light-years that separate us from these distant worlds, we employ multidimensional hacking techniques to identify vulnerabilities that traverse both space and time. Our quantum encryption methods and temporal intrusion testing redefine the limits of protection. Through these endeavors, we ensure that the burgeoning civilizations of far-off planets are shielded from the digital tides that threaten their progress."
  },
"3": {
    "author": "Dr. ARM",
    "cover_image": "https://img.freepik.com/premium-photo/the-midjourney-multiverse-started-as-an-experiment-cosmic-mysterious-generative-ai-aig15_31965-171036.jpg?w=2000",
    "title": "The Multiverse's Digital Guardians: Dr. ARM's Perspective",
    "content": "As the founder of Multidimension Cybersecurity, I've been privileged to lead a team of Ethical Multidimensional Hackers (EMDHs) who wield the power to traverse the multidimensional fabric of the universe. Our mission extends beyond mere cybersecurity; it encompasses the protection of entire dimensions. With a time machine at our disposal and an uncanny ability to navigate the folds of reality, we've uncovered vulnerabilities that defy conventional understanding. Through our combined efforts, we serve as the guardians of the multiverse's digital frontiers, standing resolute against threats that span all of existence."
  },
   "4": {
    "author": "Dr. ARM",
    "cover_image": "https://scx1.b-cdn.net/csz/news/800a/2023/entangled-atoms-across.jpg",
    "title": "Quantum Guardians: Dr. ARM's Odyssey Through Galactic Networks",
    "content": "Embarking on an extraordinary journey, Multidimension Cybersecurity's Dr. ARM unravels the mysteries of galactic networks that span the Milky Way. With quantum cryptography as our shield and multidimensional hacking as our sword, we traverse both space and time to unearth vulnerabilities that menace the planets in our celestial neighborhood. Our commitment to rewriting the rules of cybersecurity transcends the boundaries of our terrestrial realm. Through cutting-edge innovations and visionary prowess, we stand firm as the quantum guardians of the galaxy, preserving the sanctity of digital existence among the stars."
  }
    }
    for blog_id, blog_data in allblogs.items():
        c.execute('INSERT INTO entry (cover, title, author, content) VALUES (?, ?, ?, ?)',
                  (blog_data['cover_image'], blog_data['title'], blog_data['author'], blog_data['content']))
    
    conn.commit()
    conn.close()



@app.route('/')
def index():
    return render_template('index.html')

@app.route('/page')
def page():
    page = request.args.get('page')
    try:
        with open(f'templates/{page}') as f:
            page_content = f.read()
    except FileNotFoundError:
        page_content = f'Page {page} not found'  # Setting to None to trigger "Page Not Found" prompt
    except Exception:
        page_content = f'Page {page} not found' # Setting to None to trigger "Page Not Found" prompt

    if page_content is None:
        page_content = f'Page {page} not found'

    return render_template('page.html', page_content=page_content)

@app.route('/blog')
def display_blogs():
    conn = sqlite3.connect('blog.db')
    c = conn.cursor()
    c.execute('SELECT * FROM entry')
    blogs = c.fetchall()
    htmlforblogs = ''
    for blog in blogs:
        short_description = blog[4][:100] + "..." 
        htmlforblogs += f'''
        <div class="xl:w-1/4 md:w-1/2 p-4">
            <div class="bg-gray-100 p-6 rounded-lg">
                <img class="h-40 rounded w-full object-cover object-center mb-6" src="{blog[1]}" alt="content">
                <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">{blog[3]}</h3>
                <h2 class="text-lg text-gray-900 font-medium title-font mb-4">{blog[2]}</h2>
                <p class="leading-relaxed text-base">{short_description}</p>
                <a href="/readblog/{blog[0]}" class="text-indigo-500 inline-flex items-center mt-3">Read More</a>
            </div>
        </div>
        '''
    return render_template('blog.html', htmlforblogs=htmlforblogs,
                           blog_title="Discover Our Multidimensional Adventures",
                           blog_description="Join us as we delve into the cosmos, safeguarding the digital fabric of the universe.")

@app.route('/readblog/<blog_id>')
def read_blog(blog_id):
    conn = sqlite3.connect('blog.db')
    c = conn.cursor()
    c.execute('SELECT * FROM entry WHERE id = ' + blog_id)
    blog = c.fetchone()
    
    return render_template('readblog.html', cover_image=blog[1],
                           author=blog[3], author_description="Dr. ARM is the founder of Multidimension Cybersecurity, a company that specializes in safeguarding the digital fabric of the universe. With a time machine at his disposal and an uncanny ability to navigate the folds of reality, he's uncovered vulnerabilities that defy conventional understanding. Through his combined efforts, he serves as the guardian of the multiverse's digital frontiers, standing resolute against threats that span all of existence.",
                           blog_content=blog[4])


@app.route('/searchblog')
def search_blogs():
    return render_template('search.html')

@app.route('/search')
def vulnerable_search_blogs():
    query = request.args.get('query')
    conn = sqlite3.connect('blog.db')
    c = conn.cursor()
    try:
        sql_query = 'SELECT * FROM entry WHERE title LIKE "%' + query + '%" OR content LIKE "%' + query + '%"'
        c.execute(sql_query)
        search_results = c.fetchall()
        conn.close()
        return render_template('search_results.html', search_results=search_results, query=query)
    except:
        return render_template('search_results.html', search_results=[], query=query)



if __name__ == '__main__':
    app.run(debug=True)
