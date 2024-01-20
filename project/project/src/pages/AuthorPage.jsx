import { useState, useEffect } from "react"
import { Link, useLocation } from "react-router-dom";

import { useParams } from 'react-router-dom'

import {WorkOverview} from '../components/WorkOverview'
import { useNavigate } from 'react-router-dom'

import axios, { isAxiosError } from "axios";

import { CustomLink } from "../components/CustomLink";


const AutorPage = () => {
    const navigate = useNavigate();

    let {idAuthor} = useParams();

    let [author, setAuthor] = useState([]);
    let [works, setWorks] = useState([]);

    let [maxPage, setMPage] = useState(0);
    let [current_page, setCPage] = useState(0);

    useEffect(() => {
        axios
        .post(`http://fanlib-api.ru/select/author`, null, {params: {
            'UserID': idAuthor
        }})
        .then((response) => {
            setAuthor(response.data);
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
                navigate('/*')
            }
        });
    }, [idAuthor]);

    useEffect(() => {loadWorks()},[current_page, idAuthor]);

    async function loadWorks () {
        axios
        .post(`http://fanlib-api.ru/select/works`, null, {params: {
            'user_id': idAuthor,
            'type': 'byAuthor',
            'page': current_page,
        }})
        .then((response) => {
            // setWorks(response.data);
            setWorks(response.data.works);
            setMPage(response.data.pagecount);
            // console.log(response.data)
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data);
            }
        });
    }

    function ChangePage(UpDown) {
        if (UpDown === true) // вверх 
        {
            if (current_page+2 <= maxPage)
                setCPage(current_page+1);
        }
        else // вниз
        {
            if (current_page >= 1)
                setCPage(current_page-1);
        }
        console.log(current_page)
    }


    return (
        <div className="content">
            <div className="AuthorPage-Nickname">
                {author.nickname}
            </div>

            <div className="AuthorPage">
                <div className="AuthorPage-Info">
                    <div className="AuthorPage-About">
                        <p className="AuthorPage-BigText">О себе:</p>
                        <div className="AuthorPage-AboutText">{(author.about !== null) ? author.about : <p>Автор еще ничего не рассказал о себе</p>}</div>
                    </div>
                    
                    <div className="AuthorPage-LastWorks">
                        <p className="AuthorPage-BigText">Работы автора:</p>

                        <div className="AuthorPage-Works">
                            {
                                (works.message !== "No post") 
                                ? (works.map(el => (
                                    <div key={el.work_id}>
                                        <WorkOverview WorkId={el.work_id}/>
                                    </div>
                                )))
                                : (<p>К сожалению, не найдено работ, опубликованных автором</p>)
                            }
                            {
                                (works.message !== "No post") && 
                                (<div className="PageNavContainer">
                                    <button className="Input-button" onClick={() => {ChangePage(false)}}>Назад</button>
                                    <span>{current_page+1}/{maxPage}</span>
                                    <button className="Input-button" onClick={() => {ChangePage(true)}}>Вперед</button>
                                </div>) 
                            }
                        </div>
                    </div>
                </div>


            </div>

        </div>
    )
}

export {AutorPage}