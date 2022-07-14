from bs4 import BeautifulSoup
from serpapi import GoogleSearch
import requests, lxml, os
import pandas as pd
import numpy as np

def main():
    if not os.path.exists('bib_all.csv'):
        headers = {
            'User-agent':
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582"
        }
        # Sergios
        #params = {
        #   "user": "7vCEi-gAAAAJ",
        #   "sortby": "pubdate",
        #   "hl": "en"
        #}
        # author_listsg, title_listsg, titlelinks_listsg, journal_listsg, year_listsg = get_articles(headers, params)
        params = {
            "api_key": os.getenv("API_KEY"),
            "engine": "google_scholar_author",
            "author_id": "7vCEi-gAAAAJ",
            "hl": "en",
            "sort": "pubdate",
            "num": 100,
            "start": 0,
        }
        npages = 3  # = ceil( Amount of total articles / 100 (=num) )
        author_listsg, title_listsg, titlelinks_listsg, journal_listsg, year_listsg = get_articles_serpapi(params, npages)
        # Thomas
        #params = {
        #    "user": "Oo6NZZcAAAAJ",
        #    "sortby": "pubdate",
        #    "hl": "en"
        #}
        #author_listtk, title_listtk, titlelinks_listtk, journal_listtk, year_listtk = get_articles(headers, params)
        params = {
            "api_key": os.getenv("API_KEY"),
            "engine": "google_scholar_author",
            "author_id": "Oo6NZZcAAAAJ",
            "hl": "en",
            "sort": "pubdate",
            "num": 100,
            "start": 0,
        }
        npages = 2  # = ceil( Amount of total articles / 100 (=num) )
        author_listtk, title_listtk, titlelinks_listtk, journal_listtk, year_listtk = get_articles_serpapi(params, npages)

        df = pd.DataFrame({'authors': author_listsg + author_listtk, 'title': title_listsg + title_listtk,
                           'titlelink': titlelinks_listsg + titlelinks_listtk,
                           'journal': journal_listsg + journal_listtk,
                           'year': year_listsg + year_listtk})
        df = df.drop_duplicates('title')
        df.to_csv('bib_all.csv')
    else:
        df = pd.read_csv('bib_all.csv')

    hf = open('bib.dat', 'w')
    for year in range(2020, 2005, -1):
        dfcurr = df.loc[df['year'] == year]
        if dfcurr.empty:
            continue
        hf.write(f'<h4>{year}</h4><p>\n')
        for index, row in dfcurr.iterrows():
            if row['year'] <= 2020:
                hf.write(row['authors'] + '<br>\n')
                hf.write('<b><a href="' + row['titlelink'] + '" target="_blank">' + row['title'] + '</a></b><br>\n')
                if not isinstance(row['journal'], str) and np.isnan(row['journal']):
                    hf.write(f'{int(row["year"])}</p><br>\n')
                else:
                    hf.write('<i>' + row['journal'] + f'</i>, {int(row["year"])}</p><br>\n')
        hf.write('<hr/>')
    hf.close()



def get_articles_serpapi(params, npages):
    author_list = list()
    title_list = list()
    titlelinks_list = list()
    journal_list = list()
    year_list = list()
    for page in range(npages):
        params['start'] = page * params['num']
        search = GoogleSearch(params)
        results = search.get_dict()
        for article in results['articles']:
            article_title = article['title']
            article_link = article['link']
            article_authors = article['authors']
            if 'publication' in article.keys():
                article_publication = article['publication']
            else:
                article_publication = ''
            article_journal = ','.join(article_publication.split(',')[:-1])
            cited_by = article['cited_by']['value']
            cited_by_link = article['cited_by']['link']
            article_year = article['year']
            author_list.append(article_authors)
            title_list.append(article_title)
            titlelinks_list.append(article_link)
            journal_list.append(article_journal)
            year_list.append(article_year)

    return author_list, title_list, titlelinks_list, journal_list, year_list


def get_articles(headers, params):
    html = requests.get('https://scholar.google.com/citations', headers=headers, params=params).text
    soup = BeautifulSoup(html, 'lxml')
    print('Article info:')
    author_list = list()
    title_list = list()
    titlelinks_list = list()
    journal_list = list()
    year_list = list()
    for article_info in soup.select('#gsc_a_b .gsc_a_t'):
        title = article_info.select_one('.gsc_a_at').text
        title_link = f"https://scholar.google.com{article_info.select_one('.gsc_a_at')['href']}"
        authors = article_info.select_one('.gsc_a_at+ .gs_gray').text
        publications = article_info.select_one('.gs_gray+ .gs_gray').text
        year = int(publications.split(',')[-1].strip(' '))
        journal = ','.join(publications.split(',')[:-1])
        print(f'Title: {title}\nTitle link: {title_link}\nArticle Author(s): {authors}\nArticle Publication(s): {publications}\n')
        author_list.append(authors)
        title_list.append(title)
        titlelinks_list.append(title_link)
        journal_list.append(journal)
        year_list.append(year)

    return author_list, title_list, titlelinks_list, journal_list, year_list
  


if __name__ == '__main__':
    main()