import React from 'react';
import { useState, useEffect } from "react";
import { useParams } from 'react-router-dom';

import axios, {isAxiosError} from 'axios';

import { CustomLink } from '../components/CustomLink';

import { useOutlet } from 'react-router-dom';

import { Notfoundpage } from './NotFoundPage';

const FandomPage = () => {

    const outlet = useOutlet();


    let {idFandom} = useParams();
    let [fandom, setFandom] = useState([]);

    useEffect(() => {
        axios
            .post(`http://fanlib-api.ru/select/fandom`, null, {params:{
                'FandomId': idFandom,
            }})
            .then((response) => {
                setFandom(response.data[0]);
                // console.log(response.data[0]);
            })
            .catch((error) => {
                if (isAxiosError(error))
                {
                    setFandom(error.response.data);
                    // console.log(error.response.data.message);
                }
            });
    }, [idFandom]);

    // console.log(fandom)

    if (fandom.status !== false)
    {
        let nv = "";
        let sp = "";

        if (idFandom > 0)
        {
            nv = <nav className="AuthorPage-nav">
                <CustomLink to=".">О фандоме</CustomLink>
                <CustomLink to="./characters">Персонажи</CustomLink>
                <CustomLink to="./works">Работы</CustomLink>
            </nav>

            sp = <span>Автор исходного произведения:</span>
        }

        return (
            <div className='content'>
                <h1>{fandom.name}</h1>

                {nv}

                <div className='Fandom-About'>
                {
                    outlet || 
                    <>
                        <h1>О фандоме </h1>
                        <div> {sp} {fandom.author}</div>
                        <div>{fandom.about_fandom}</div>
                    </>
                }
                </div>

            </div>
        )
    }
    else
    {
        return(<Notfoundpage />)
    }
    
}

export {FandomPage}