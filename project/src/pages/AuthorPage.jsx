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

    useEffect(() => {
        axios
            .get(`http://fanlib-api.ru/users/one/${idAuthor}`)
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

    useEffect(() => {
        axios
            .post(`http://fanlib-api.ru/AuthorWorks/last`, null, {params: {
                'user_id': idAuthor,
            }})
            .then((response) => {
                setWorks(response.data);
                // console.log(response.data)
            })
            .catch((error) => {
                if (isAxiosError(error))
                {
                    console.log(error.response.data.message);
                }
            });
    }, [idAuthor]);

    // console.log(works);


    return (
        <div className="content">
            <div className="AuthorPage-Nickname">
                {author.nickname}
            </div>
            
            {/* <nav className="AuthorPage-nav">
                <CustomLink to=".">Главная</CustomLink>
                <CustomLink to="./works">Работы автора</CustomLink>
            </nav> */}

            <div className="AuthorPage">
                <div className="AuthorPage-Info">
                    <div className="AuthorPage-About">
                        <p className="AuthorPage-BigText">О себе:</p>
                        <div className="AuthorPage-AboutText">{(author.about !== null) ? author.about : <p>Автор еще ничего не рассказал о себе</p>}</div>
                    </div>
                    
                    <div className="AuthorPage-LastWorks">
                        <p className="AuthorPage-BigText">Последние работы:</p>

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
                        </div>

                    </div>
                </div>


            </div>

        </div>
    )
}

export {AutorPage}