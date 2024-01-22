import axios, {isAxiosError} from 'axios';
import React from 'react';
import { useState, useEffect } from "react";
import { Link, useSearchParams } from 'react-router-dom';

import Filter from '../components/SearchFilter';

// class FandomSearch extends Component {
  
//     render() {
//         return (
//             <div className='content'>
//                 <h1>Поиск Фандомов</h1>
//                 <form className='Input-form'>
//                     <input className='Input-text' placeholder='Название фандома'></input>
//                     <button className='Input-button' type='submit'>Поиск</button>
//                 </form>
//             </div>
//         )
//     }
// }

const FandomSearch = () => {

    const[fandoms, setFandoms] = useState([]);
    const[searchParams, setSearchParams] = useSearchParams();

    const fandomQuerry = searchParams.get('name') || '';


    useEffect(() => {
        if (fandomQuerry !== '')
        {
            axios
            .post(`http://fanlib-api.ru/search/fandom`, null, { params: {
                name: fandomQuerry,
            }})
            .then((response) => {
                setFandoms([]);
                setFandoms(response.data);
                console.log(response.data);
            })
            .catch((error) => {
                if (isAxiosError(error))
                {
                    console.log(error.response.data.message);
                }
            });
        }
        else 
        {
            setFandoms(undefined);
        }
    }, [fandomQuerry]);

    // console.log(fandoms);

    return (
        <div className='content'>
            <h1>Поиск Фандомов</h1>

            <Filter fandomQuerry={fandomQuerry} setSearchParams={setSearchParams} />

            <div className='SearchContainter'>
                {
                    fandoms !== undefined &&
                    (fandoms.status !== false ? (
                        fandoms.map(fand => (
                            <li key = {fand.fandom_id}>
                                <Link className='Search-link' to={`../fandom/${fand.fandom_id}`}>{fand.name}</Link>
                            </li>
                        ))
                    ) : <p>Ничего не найдено</p>)
                }
            </div>
        </div>
    )
}

export {FandomSearch}


