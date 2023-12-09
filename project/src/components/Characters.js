import React from 'react'

import { Link, useParams } from 'react-router-dom'
import { useState, useEffect } from "react"
import axios, { isAxiosError }  from 'axios'

import { Notfoundpage } from '../pages/NotFoundPage'

const Characters = () => {

    let {idFandom} = useParams();
    let [characters, setCharacters] = useState([]);

    useEffect(() => {
        axios
            .post(`http://fanlib-api.ru/characters/fromfandom/`, null, {params: {
                fandom_id: idFandom,
            }})
            .then((response) => {
                setCharacters(response.data);
                console.log(response.data[0]);
            })
            .catch((error) => {
                if (isAxiosError(error))
                {
                    setCharacters(error.response.data);
                    console.log(error.response.data.message);
                }
            });
    }, [idFandom]);

    if (idFandom > 0)
    {
        return (
            <>
                <h1>Персонажи: </h1>

                {
                    characters !== undefined &&
                        characters.map(el => (
                            <li key = {el.character_id}>{el.character_name}</li>
                        ))
                }
            </>
        )
    }
    else
    {
        return(<Notfoundpage />)
    }
}

export {Characters}